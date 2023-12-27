<?php

namespace SpiralPress\App\Http\Controllers\Site ;

use App\Models\Post;
use App\Models\Project;
use framework\Http\Controller;
use HttpRequest;
use HttpRequestParameter;

class SiteController extends Controller
{

    public function index(array $vars)
    {
        $projectId = (defined('PROJECTID') ? PROJECTID : "");
        $project = Project::find($projectId);

        $this->render($project, $project->topPage , false);
    }

    public function render($project , $shareId , $showHead = true){
        $post = Post::findByPostShareIdAndProjectId($shareId , $project->projectId);

        $httpRequestParameter = new HttpRequestParameter();
        $httpRequest = new HttpRequest();
        $httpRequest->setUrl($post->jsonContents);
        $json = $httpRequest->get($httpRequestParameter, true);
        $codeHtml = '';
        if(!empty($json['blocks'])){
            $codeHtml = codeToHtml()->render($json['blocks']);
        }

        $codeHtml = preg_replace_callback('/http:\/\/%cms:post:url:([a-zA-Z0-9]+)%/', function ($matches) {
            $id = $matches[1];
            $url = url('post.show', ['postShareId' => $id]);
            return $url;
        }, $codeHtml);

        $codeHtml = preg_replace_callback('/%cms:post:url:([a-zA-Z0-9]+)%/', function ($matches) {
            $id = $matches[1];
            $url = url('post.show', ['postShareId' => $id]);
            return $url;
        }, $codeHtml);

        
        $httpRequestParameter = new HttpRequestParameter();
        $httpRequest = new HttpRequest();
        $httpRequest->setUrl($project->globalMenuJson);
        $json = $httpRequest->get($httpRequestParameter, true);
        $menuHtml = '';
        if(!empty($json)){
            $menuHtml = menuJsonToHtml()->render($json);
        }
        
        $options = JSON_UNESCAPED_SLASHES;
        echo view("site.index",[
            'project' => $project,
            'post' => $post,
            'showHead' => $showHead,
            'codeHtmltoJson' => json_encode(['html' => $codeHtml],$options),
            'menuHtml' => $menuHtml,
            'datatheme' => $project->theme
        ])->render();
    }

    public function create(array $vars)
    {
        //
    }

    public function store(array $vars)
    {
        //
    }

    public function show(array $vars)
    {
        $projectId = (defined('PROJECTID') ? PROJECTID : "");
        $project = Project::find($projectId);
        $this->render($project, $vars['postShareId'] );
    }

    public function edit(array $vars)
    {
        //
    }

    public function update(array $vars)
    {
        //
    }

    public function destroy(array $vars)
    {
        //
    }
}
