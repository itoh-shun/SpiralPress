<?php

namespace SpiralPress\App\Http\Middreware ;

use Csrf;
use framework\Http\Middleware\Middleware;
use framework\Http\Middleware\MiddlewareInterface;

class ProjectMiddleware extends Middleware implements
    MiddlewareInterface
{
    public function process(array $vars)
    {
        $projects = getProjects();
        foreach($projects as $key => $project){
            if($project->projectId === $vars['projectId']){
                return true;
            }
        }

        return false;
    }
}
