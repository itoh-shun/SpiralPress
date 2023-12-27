<?php

use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use framework\Http\Request;
use framework\SpiralConnecter\SpiralRedis;
use SpiralPress\App\Library\CodexToHtml;
use SpiralPress\App\Library\MenuJsonToHtml;

function getProjects($fetch = false) {
    $redis = new SpiralRedis();
    $projects = $redis->get('sp.projects');
    $projects = json_decode($projects);
    if(empty($projects) || $fetch){
        $projects = Project::all();
        $redis->set('sp.projects' , json_encode($projects));
    }
    return collect($projects);
}

function getCategories($projectId , $fetch = false) {
    $redis = new SpiralRedis();
    $categories = $redis->get("sp.categories.".$projectId);
    $categories = json_decode($categories);
    if(empty($categories) || $fetch){
        $categories = Category::allByProjectId($projectId);
        $redis->set("sp.categories.".$projectId , json_encode($categories));
    }
    return collect($categories);
}

function codeToHtml(){
    return new CodexToHtml([
        'raw' => function($html){
            return "<div class='block'>".$html."</div>";
        },
        'header' => function($level,$text) {
            return "<div class='block'><h{$level}>{$text}</h{$level}></div>";
        },
        'paragraph' => function($text) {
            return "<div class='block'><p>{$text}</p></div>";
        },
        'image' => function($caption, $file, $stretched, $withBackground, $withBorder) {
            $containerClasses = ['block'];
            $containerClasses[] = ($stretched )? 'with-stretched' : '';
            $containerClasses[] = ($withBackground )? 'with-background' : '';
            $containerClasses[] = ($withBorder )? 'with-border' : '';
            $imageClasses = [];
            return "<div class=\"".implode(' ',$containerClasses)."\"><img src=\"{$file['url']}\" title=\"{$caption}\" alt=\"{$caption}\" class=\"".implode(' ',$imageClasses)."\"></div>";
        },
        'embed' => function($caption, $embed, $height, $service, $source, $width) {
            // ここで各サービスに応じた埋め込み処理を行う
            switch ($service) {
                case 'youtube':
                    // YouTubeの処理
                    $embedHtml = "<iframe src=\"{$embed}\" style=\"width:100%; height:320px;\" frameborder=\"0\" allowfullscreen></iframe>";
                    break;
                case 'vimeo':
                    // Vimeoの処理
                    $embedHtml = "<iframe src=\"{$embed}\" style=\"width:100%; height:320px;\" frameborder=\"0\" allowfullscreen></iframe>";
                    break;
                // 他のサービスの処理をここに追加
                default:
                    $embedHtml = "<iframe src=\"{$embed}\" style=\"width:100%;\" frameborder=\"0\"></iframe>";
            }
            $containerClasses = [];
            $containerClasses[] = 'block';
            return "<div class=\"".implode(' ', $containerClasses)."\"><div class=\"embed-responsive\">{$embedHtml}</div>".(!empty($caption) ? "<p class=\"caption\">{$caption}</p>" : "")."</div>";
        },
        'linkTool' => function($link , $meta){
            $parsedUrl = parse_url($link);

            $containerClasses = ['block'];

            $domain = $parsedUrl['host'];
            return "
            <div class=\"".implode(' ', $containerClasses)."\">
                <a class='link-tool' target='_blank' rel='nofollow noindex noreferrer' href='{$link}'>
                    <div class='image' style='background-image: url(&quot;{$meta['image']['url']}&quot;);'></div>
                    <div class='title'>{$meta['title']}</div>
                    <p class='description'>{$meta['description']}</p>
                    <span class='anchor'>{$domain}</span>
                </a>
            </div>";
        },
        'warning' => function($message , $title){
            $containerClasses = ['block'];
            return "
            <div class=\"".implode(' ', $containerClasses)."\">
                <dl class='warning'>
                    <dt>{$title}</dt>
                    <dd>{$message}</dd>
                </dl>
            </div>";
        },
        'list' => function($items , $style){
            
            // EditorJSデータからリストのスタイルを決定
            $isOrdered = $style === 'ordered';
            
            // HTMLに変換
            $htmlList = arrayToHtmlList($items, $isOrdered);
            
            return "<div class='block'>{$htmlList}</div>";
        },
        'quote' => function($alignment , $caption , $text){
            return "<div class='block quote {$alignment}'>
            <blockquote>
                <p>\"{$text}\"</p>
            </blockquote>
            <figcaption>
                <div>
                    {$caption}
                </div>
            </figcaption>
            </div>";
        },
        'checklist' => function($items){
            $html = '';
            $html .= "<div class='block checklist'>";
            foreach($items as $item){
                $html .= "<div class='form-control'>
                    <label class='label'>
                        <input type='checkbox' checked='{$item['checked']}' class='checkbox' disabled />
                        <span class='label-text'>{$item['text']}</span>
                    </label>
                </div>";
            }
            $html .= "</div>";
            return $html;
        },
        'delimiter' =>  function(){
            return "<div class='block delimiter'></div>";
        },
        'table' =>  function($content , $withHeadings = false){

            $html = '<table class="table">';
            $tdTag = ($withHeadings)? 'th' : "td";
            foreach($content as $tr){
                $html .= "<tr>";
                foreach($tr as $td){
                    $html .= "<{$tdTag}>{$td}</{$tdTag}>";
                }
                $html .= "</tr>";
                $tdTag = "td";
            }
            $html .= '</table>';

            return "<div class='block'>{$html}</div>";
        },
        'code' => function($code){
            	
            $str = str_replace(array("\r\n", "\r", "\n"), "\n", $code);
            $arr = explode("\n", $str);

            $html = '';
            foreach($arr as $key => $text){
                $html .= "<pre data-prefix='".($key + 1)."'><code>{$text}</code></pre>";
            }
            return "<div class='block'>
                        <div class='block mockup-code'>
                            {$html}
                        </div>
                    </div>";
        },
        'postListTool' => function($categoryShareId ,$id, $limit , $viewType){
            $projectId = (defined('PROJECTID') ? PROJECTID : "");
            $limit = ($limit > 0 && $limit <= 100) ? $limit : 10;
            $request = new Request();
            $posts = Post::instance()->where('projectId', $projectId)->page($request->get($id.'_'.'page', 1))->orderBy('id' , 'desc')->where('categoryShareId' , $categoryShareId)->paginate($limit);
            $posts->setUniqId($id);
            
            if($viewType == 'title'){
                $html = "<table class='table my-4'>";
                foreach( $posts->getData() as $post){
                    $html .= "<tr>
                        <td>{$post->createAt}</td>
                        <td><a href='".url('post.show', ['postShareId' => $post->postShareId])."'>{$post->title}</a></td>
                    </tr>";
                }
                $html .= "</table>";
            } else {
                $html = "";
                foreach( $posts->getData() as $post){
                $html .= "<a class='card w-full bg-base-100 shadow my-4' href='".url('post.show', ['postShareId' => $post->postShareId])."'>
                    <div class='card-body'>
                        <span class='text-sm'>{$post->createAt}</span>
                        <p class='card-title hover:underline'>{$post->title}</p>
                    </div>
                </a>";
                }
            }
            $html = "<div class='block'>
                    {$html}
                {$posts->links()}
            </div>";
            return $html;
        
        },
        'attaches' => function($file , $title){
            $colors = [     
            'doc'=>'#1483E9',
            'docx'=>'#1483E9',
            'odt'=>'#1483E9',
            'pdf'=>'#DB2F2F',
            'rtf'=>'#744FDC',
            'tex'=>'#5a5a5b',
            'txt'=>'#5a5a5b',
            'pptx'=>'#E35200',
            'ppt'=>'#E35200',
            'mp3'=>'#eab456',
            'mp4'=>'#f676a6',
            'xls'=>'#11AE3D',
            'html'=>'#2988f0',
            'htm'=>'#2988f0',
            'png'=>'#AA2284',
            'jpg'=>'#D13359',
            'jpeg'=>'#D13359',
            'gif'=>'#f6af76',
            'zip'=>'#4f566f',
            'rar'=>'#4f566f',
            'exe'=>'#e26f6f',
            'svg'=>'#bf5252',
            'key'=>'#00B2FF',
            'sketch'=>'#FFC700',
            'ai'=>'#FB601D',
            'psd'=>'#388ae5',
            'dmg'=>'#e26f6f',
            'json'=>'#2988f0',
            'csv'=>'#11AE3D'];
            return "<div class='block'>
                        <div class='cdx-attaches cdx-attaches--with-file'>
                            <div class='cdx-attaches__file-icon'>
                                <div class='cdx-attaches__file-icon-background' style='background-color:{$colors[$file['extension']]};'></div>
                                <div class='cdx-attaches__file-icon-label' title='{$file['extension']}' style='background-color: {$colors[$file['extension']]}'>{$file['extension']}</div>
                            </div>
                            <div class='cdx-attaches__file-info'>
                                <div class='cdx-attaches__title'>{$title}</div>
                                <div class='cdx-attaches__size' data-size='KiB'>{$file['size']}</div>
                            </div>
                            <a class='cdx-attaches__download-button' href='{$file['url']}' target='_blank' rel='nofollow noindex noreferrer'>
                            </a>
                        </div>
                    </div>";
        }
    ],

    
    true);
}
function arrayToHtmlList($items, $isOrdered = false) {
    if (empty($items)) {
        return '';
    }

    $listType = $isOrdered ? 'ol' : 'ul';
    $html = "<$listType>";
    foreach ($items as $item) {
        $html .= "<li>" . htmlspecialchars($item['content']);
        $html .= arrayToHtmlList($item['items'], $isOrdered);
        $html .= "</li>";
    }
    $html .= "</$listType>";

    return $html;
}

function menuJsonToHtml(){
    return new MenuJsonToHtml();
}
