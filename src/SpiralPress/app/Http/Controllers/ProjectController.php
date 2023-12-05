<?php

namespace SpiralPress\App\Http\Controllers ;

use App\Models\Project;
use BladeOneCsrf;
use Csrf;
use framework\Http\Controller;
use framework\Http\Request;
use framework\Http\Response;
use framework\Library\BladeLikeEngine\BladeOneCustom;

class ProjectController extends Controller
{

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
        $project = new Project;
        $project->projectName = $this->request->projectName;
        $project->projectId = uniqid();
    
        // プロジェクトをデータベースに保存
        $project->save();
    
        getProjects(true);
        
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
        echo view('html.projects.edit',[
            'projectId' => $project->projectId,
            'project' => $project,
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
        ],[
            'projectName' => 'プロジェクト名',
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
