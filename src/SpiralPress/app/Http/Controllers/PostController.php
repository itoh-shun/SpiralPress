<?php

namespace SpiralPress\App\Http\Controllers ;

use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use App\Models\PostView;
use BladeOneCsrf;
use EditorJS\EditorJS;
use framework\Http\Controller;
use framework\Http\Request;
use HttpRequest;
use HttpRequestParameter;
use SiValidator2\Rules\ExistsRule;
use SiValidator2\Rules\RuleInterface;
use SiValidator2\Rules\UniqueRule;

class PostController extends Controller
{
    // カテゴリの一覧を表示
    public function index($vars)
    {
        $categories = getCategories($vars['projectId']);
        $posts = Post::allByProjectId($vars['projectId']);

        $postView = [];
        foreach($posts as $key => $p){
            $p = collect((array)$p);
            $p->categoryName = '';
            $postView[] = $p;
        }
        foreach($postView as $key => $p){
            foreach($categories as $c){
                if($p->categoryShareId === $c->categoryShareId){
                    $postView[$key]->categoryName = $c->categoryName;
                    break;
                }
            }
        }

        echo view('html.posts.index', [
            'posts' => $postView,
            'projectId' => $vars['projectId'],
        ]);
    }

    // カテゴリの作成フォームを表示
    public function create($vars)
    {
        $categories = getCategories($vars['projectId']);

        $categoriesList = [];
        foreach( $categories as $category){
            $categoriesList[] = $category;
        }

        echo view('html.posts.create',[
            'categories' => $categoriesList,
            'categoryShareId' => $this->request->categoryShareId,
            'projectId' => $vars['projectId'],
            'isError' => $this->request->session()->pull('isError'),
            'message' => $this->request->session()->pull('message'),
            'validate' =>  $this->request->session()->pull('validate')
        ]);
    }

    // 新しいカテゴリを保存
    public function store($vars)
    {
        if(BladeOneCsrf::validate() !== true){
            $this->request->session()->put('isError', true);
            $this->request->session()->put('message' , 'CSRFトークンの有効期限が切れました。');
            redirect('categories.create',$vars);
        }
        
        $validatedData = $this->request->validate([
            'categoryShareId' => ['required',
            (new ExistsRule('sp_category', 'categoryShareId'))->where(function($model) use ($vars){
                return $model->where('projectId', $vars['projectId']);
            })],
            'title' => ['required','max_bytes:256']
        ],[
            'categoryShareId' => 'カテゴリ選択',
            'title' => 'タイトル',
        ]);

        if($validatedData->isError()) {
            $this->request->session()->put('isError', true);
            $this->request->session()->put('message', 'エラーが発生しました');
            $this->request->session()->put('validate', $validatedData->getResults());
            redirect('posts.create',$vars);
        }
        $id = uniqid();
        $post = new Post();
        $post->postId = $id;
        $post->postShareId = $id;
        $post->categoryShareId = $this->request->categoryShareId;
        $post->title = $this->request->title;
        $post->projectId = $vars['projectId'];
        $post->save();
        
        redirect('posts.index',$vars);
    }

    public function show(array $vars)
    {
    }

    public function edit($vars)
    {
        $post = Post::findByPostIdAndProjectId($vars['id'], $vars['projectId']);

        $validate = $this->request->session()->pull('validate');
        $json = $validate['content']['value'];
        if(!$json){
            $httpRequestParameter = new HttpRequestParameter();
            $httpRequest = new HttpRequest();
            $httpRequest->setUrl($post->jsonContents);
            $json = json_encode($httpRequest->get($httpRequestParameter));           
        }
        $categories = getCategories($vars['projectId']);

        $categoriesList = [];
        foreach( $categories as $category){
            $categoriesList[] = $category;
        }

        echo view('html.posts.edit', [
            'categories' => $categoriesList,
            'projectId' => $vars['projectId'],
            'post' => $post,
            'contentJson' => $json ?? "{}",
            'isError' => $this->request->session()->pull('isError'),
            'message' => $this->request->session()->pull('message'),
            'validate' =>  $validate
        ]);
    }
    
    // カテゴリの更新
    public function update($vars)
    {
        $post = Post::findByPostIdAndProjectId($vars['id'], $vars['projectId']);
        if(is_array($this->request->content)){
            $this->request->set('content',json_encode($this->request->get('content',[])));
        }

        $this->request->set('content',$this->request->getUrldecode('content'));

        $validatedData = $this->request->validate([
            'title' => ['required','max_bytes:128'],
            'categoryShareId' => ['required',
            (new ExistsRule('sp_category', 'categoryShareId'))->where(function($model) use ($vars){
                return $model->where('projectId', $vars['projectId']);
            })],
            'content' => ['required','json'],
        ],[
            'categoryShareId' => 'カテゴリ選択',
            'title' => 'タイトル',
            'content' => 'コンテンツ',
        ]);

        $validate = $validatedData->getResults();
        $validate['content']['value'] = json_encode(json_decode($validate['content']['value']));//文字化け対策

        if(BladeOneCsrf::validate() !== true){
            $this->request->session()->put('isError', true);
            $this->request->session()->put('message' , 'CSRFトークンの有効期限が切れました。');
            $this->request->session()->put('validate', $validatedData->getResults());
            redirect('posts.edit',$vars);
        } 

        if($validatedData->isError()) {
            $this->request->session()->put('isError', true);
            $this->request->session()->put('message', 'エラーが発生しました');
            $this->request->session()->put('validate', $validatedData->getResults());
            redirect('posts.edit',$vars);
        }

        $post->updateAt = 'now';
        $post->categoryShareId = $this->request->categoryShareId;
        $post->title = $this->request->title;
        $post->saveWithJsonFile($this->request->content);
        
        $this->request->session()->put('message' , '保存しました！');
        redirect('posts.edit',$vars);
    }

    public function destroy(array $vars)
    {
        //
    }
}
