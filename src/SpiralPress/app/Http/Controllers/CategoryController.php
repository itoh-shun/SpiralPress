<?php

namespace SpiralPress\App\Http\Controllers ;

use App\Models\Category;
use App\Models\Media;
use App\Models\Post;
use BladeOneCsrf;
use framework\Http\Controller;
use framework\Http\Request;
use SiValidator2\Rules\RuleInterface;
use SiValidator2\Rules\UniqueRule;

class CategoryController extends Controller
{
    // カテゴリの一覧を表示
    public function index($vars)
    {
        $categories = Category::allByProjectId($vars['projectId']);

        echo view('html.categories.index', [
            'categories' => $categories,
            'projectId' => $vars['projectId'],
        ]);
    }

    // カテゴリの作成フォームを表示
    public function create($vars)
    {
        echo view('html.categories.create',[
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
            'categoryName' => ['required','max_bytes:128',
            (new UniqueRule('sp_category', 'categoryName'))->where(function($model) use ($vars){
                return $model->where('projectId', $vars['projectId']);
            })
            ],
        ],[
            'categoryName' => 'カテゴリ名',
        ]);
        
        if($validatedData->isError()) {
            $this->request->session()->put('isError', true);
            $this->request->session()->put('message', 'エラーが発生しました');
            $this->request->session()->put('validate', $validatedData->getResults());
            redirect('categories.create',$vars);
        }
        $id = uniqid();
        
        $category = new Category();
        $category->categoryId = $id;
        $category->categoryShareId =  $id;
        $category->categoryName = $this->request->categoryName;
        $category->projectId = $vars['projectId'];
        $category->save();
        
        getCategories($category->projectId, true);
        redirect('categories.index',$vars);
    }

    public function show(array $vars)
    {
    }

    public function edit($vars)
    {
        $category = Category::findByCategoryIdAndProjectId($vars['id'], $vars['projectId']);

        echo view('html.categories.edit', [
            'projectId' => $vars['projectId'],
            'category' => $category,
            'isError' => $this->request->session()->pull('isError'),
            'message' => $this->request->session()->pull('message'),
            'validate' =>  $this->request->session()->pull('validate')
        ]);
    }
    
    // カテゴリの更新
    public function update($vars)
    {
        $category = Category::findByCategoryIdAndProjectId($vars['id'], $vars['projectId']);

        if(BladeOneCsrf::validate() !== true){
            $this->request->session()->put('isError', true);
            $this->request->session()->put('message' , 'CSRFトークンの有効期限が切れました。');
            redirect('categories.edit',$vars);
        }

        $validatedData = $this->request->validate([
            'categoryName' => ['required','max_bytes:128',
            (new UniqueRule('sp_category', 'categoryName'))->where(function($model) use ($vars){
                return $model->where('projectId', $vars['projectId'])->where('categoryId', $vars['id'] , "!=");
            })
            ],
        ],[
            'categoryName' => 'カテゴリ名',
        ]);

        if($validatedData->isError()) {
            $this->request->session()->put('isError', true);
            $this->request->session()->put('message', 'エラーが発生しました');
            $this->request->session()->put('validate', $validatedData->getResults());
            redirect('categories.edit',$vars);
        }
        $category->updateAt = 'now';
        $category->categoryName = $this->request->categoryName;
        $category->save();
        
        getCategories($category->projectId, true);
        redirect('categories.index',$vars);
    }

    public function postsindex(array $vars){
        $category = Category::findByCategoryIdAndProjectId($vars['categoryId'], $vars['projectId']);
        
        $categories = getCategories($vars['projectId']);
        $posts = Post::allByProjectIdAndCategoryShareId($vars['projectId'],$category->categoryShareId);

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
            'categoryShareId' => $category->categoryShareId,
            'projectId' => $vars['projectId'],
        ]);
    }

    public function destroy(array $vars)
    {
        //
    }
}
