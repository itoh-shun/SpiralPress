<?php

namespace SpiralPress\App\Http\Controllers ;

use App\Models\Media;
use App\Models\Project;
use BladeOneCsrf;
use Csrf;
use Exception;
use framework\Http\Controller;
use framework\Library\BladeLikeEngine\BladeOneCustom;
use framework\SpiralConnecter\SpiralRedis;
use HttpRequest;
use HttpRequestParameter;

class MenuController extends Controller
{
    public function index(array $vars)
    {
        $projects = getProjects(true);
        $project = collect([]);

        foreach( $projects as $key => $p){
            if($p->projectId === $vars['projectId']){
                $project = $p;
                break;
            }
        }
        $httpRequestParameter = new HttpRequestParameter();
        $httpRequest = new HttpRequest();
        $httpRequest->setUrl($project->globalMenuJson);
        $json = $httpRequest->get($httpRequestParameter);

        echo view('html.menu.index', [
            'projectId' => $vars['projectId'],
            'globalMenuJson' => $json ?? [],
        ]);
    }

    public function create(array $vars)
    {
    }

    public function store(array $vars)
    {
        BladeOneCsrf::validate(true);
        Project::instance()->setFile(
            'globalMenuJson',
            'menu.json',
            $this->request->get('items')
        )->where('projectId', $vars['projectId'])->update(
            [
                'projectId' => $vars['projectId'],
            ]
        );

        response()->json($this->request->get('items') , 200, 'success',$this->request->getRequestUri())->send();
    }

    public function show(array $vars)
    {
    }

    public function edit(array $vars)
    {
    }

    public function update(array $vars)
    {
    }

    public function destroy(array $vars)
    {
        //
    }
}
