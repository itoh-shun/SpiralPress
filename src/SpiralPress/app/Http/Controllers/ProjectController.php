<?php

namespace SpiralPress\App\Http\Controllers ;

use App\Models\Category;
use App\Models\Project;
use App\Models\Post;
use BladeOneCsrf;
use Csrf;
use framework\Http\Controller;
use SiValidator2\Rules\ExistsRule;
use framework\Http\Request;
use framework\Http\Response;
use framework\Library\BladeLikeEngine\BladeOneCustom;
use SiValidator2\Rules\InRule;

class ProjectController extends Controller
{

    public array $theme = [
        "light",
        "dark",
        "cupcake",
        "bumblebee",
        "emerald",
        "corporate",
        "synthwave",
        "retro",
        "cyberpunk",
        "valentine",
        "halloween",
        "garden",
        "forest",
        "aqua",
        "lofi",
        "pastel",
        "fantasy",
        "wireframe",
        "black",
        "luxury",
        "dracula",
        "cmyk",
        "autumn",
        "business",
        "acid",
        "lemonade",
        "night",
        "coffee",
        "winter",
        "dim",
        "nord",
        "sunset",
    ];

    public function index(array $vars)
    {
        echo view('html.projects.index');
    }

    public function create(array $vars)
    {
        echo view('html.projects.create',[
            'isError' => $this->request->session()->pull('isError'),
            'message' => $this->request->session()->pull('message'),
            'validate' =>  $this->request->session()->pull('validate')
        ]);
    }

    public function store(array $vars)
    {
        if(BladeOneCsrf::validate() !== true){
            $this->request->session()->put('isError', true);
            $this->request->session()->put('message' , 'CSRFトークンの有効期限が切れました。');
            redirect('articles.create');
        }

        // バリデーションルール
        $validatedData = $this->request->validate([
            'projectName' => ['required','max_bytes:128'],
        ],[
            'projectName' => 'プロジェクト名',
        ]);

        if($validatedData->isError()) {
            $this->request->session()->put('isError', true);
            $this->request->session()->put('message', 'エラーが発生しました');
            $this->request->session()->put('validate', $validatedData->getResults());
            redirect('projects.create');
        }

        // 新しいプロジェクトのインスタンスを作成
        /*
        $project = new Project;
        $project->projectName = $this->request->projectName;
        $project->projectId = uniqid();
    
        // プロジェクトをデータベースに保存
        $project->save();
        */
        $projectId = uniqid();
        
        Project::instance()->setFile(
            'globalMenuJson',
            'menu.json',
            "[]"
        )->create(
            [
                'projectId' => $projectId,
                'projectName' => $this->request->projectName,
            ]
        );

        getProjects(true);

        $categoryId = uniqid();
        $category = new Category();
        $category->categoryId = $categoryId;
        $category->categoryShareId =  $categoryId;
        $category->categoryName = '固定ページ';
        $category->projectId = $projectId;
        $category->save();
        
        $postId = uniqid();
        $post = new Post();
        $post->postId = $postId;
        $post->postShareId = $postId;
        $post->categoryShareId = $categoryId;
        $post->title = 'TOP';
        $post->projectId = $projectId;
        $post->save();

        $project = Project::find($projectId);
        unset($project->globalMenuJson);
        $project->topPage = $postId;
        $project->save();

        // プロジェクト一覧ページにリダイレクト
        $this->request->session()->put('message', 'プロジェクトが正常に作成されました。');
        redirect('projects.create');
    }

    public function show(array $vars)
    {
        var_dump($vars);
    }

    public function edit(array $vars)
    {
        $project = Project::find($vars['id']);
        $validator = $this->request->session()->pull('validate');
        
        $pages = Post::allByProjectId($project->projectId);

        echo view('html.projects.edit',[
            'projectId' => $project->projectId,
            'project' => $project,
            'pages' => $pages,
            'theme' => $this->theme,
            'isError' => $this->request->session()->pull('isError'),
            'message' => $this->request->session()->pull('message'),
            'validate' => $validator
        ]);
    }

    public function update(array $vars)
    {
        $project = Project::find($vars['id']);

        if(BladeOneCsrf::validate() !== true){
            $this->request->session()->put('isError', true);
            $this->request->session()->put('message' , 'CSRFトークンの有効期限が切れました。');
            redirect('articles.edit', ['id' => $project->projectId]);
        }

        // バリデーションルール
        $validatedData = $this->request->validate([
            'projectName' => ['required','max_bytes:128'],
            'topPage' => ['required', 
            (new ExistsRule('sp_posts', 'postShareId'))->where(function($model) use ($vars){
                return $model->where('projectId', $vars['id']);
            })],
            'theme' => ['nullable',new InRule(...$this->theme)]
        ],[
            'projectName' => 'プロジェクト名',
            'topPage' => 'TOPページ',
            'theme' => 'テーマ',
        ]);

        if($validatedData->isError()) {
            $this->request->session()->put('isError', true);
            $this->request->session()->put('message', 'エラーが発生しました');
            $this->request->session()->put('validate', $validatedData->getResults());
            redirect('projects.edit', ['id' => $project->projectId]);
        }
        
        $project->projectName = $this->request->projectName;
        $project->updateAt = 'now';
        unset($project->globalMenuJson);
        $project->topPage = $this->request->topPage;
        $project->theme = $this->request->theme;
        // プロジェクトをデータベースに保存
        $project->save();
    
        getProjects(true);

        // プロジェクト一覧ページにリダイレクト
        $this->request->session()->put('message', 'プロジェクト設定が正常に完了しました。');
        redirect('projects.edit', ['id' => $project->projectId]);
    }

    public function destroy(array $vars)
    {
        //
    }
}
