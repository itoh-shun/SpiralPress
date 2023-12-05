<?php

namespace SpiralPress\App\Http\Controllers ;

use App\Models\Media;
use framework\Http\Controller;

class MediaController extends Controller
{
    public function index(array $vars)
    {
        $media = Media::instance()->where('projectId',$vars['projectId'])->page($this->request->get('page', 1))->paginate(100);
        echo view('html.media.index', [
            'projectId' => $vars['projectId'],
            'media' => $media
        ]);
    }

    public function create(array $vars)
    {
    }

    public function store(array $vars)
    {
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
