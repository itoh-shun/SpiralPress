<?php

namespace SpiralPress\App\Http\Controllers ;

use framework\Http\Controller;

class ProjectController extends Controller
{

    public function index(array $vars)
    {
        echo view("html/welcome")->render();
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
