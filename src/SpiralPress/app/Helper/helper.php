<?php

use App\Models\Project;
use framework\SpiralConnecter\SpiralRedis;

function getProjects($fetch = false) {
    $redis = new SpiralRedis();
    $projects = $redis->get('wp.projects');
    $projects = json_decode($projects);
    if(empty($projects) || $fetch){
        $projects = Project::all();
        $redis->set('wp.projects' , json_encode($projects));
    }
    return collect($projects);
}
