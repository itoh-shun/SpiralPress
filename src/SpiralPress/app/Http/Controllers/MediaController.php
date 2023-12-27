<?php

namespace SpiralPress\App\Http\Controllers ;

use App\Models\Media;
use App\Models\Post;
use BladeOneCsrf;
use framework\Http\Controller;
use HttpRequest;
use HttpRequestParameter;

class MediaController extends Controller
{
    public function index(array $vars)
    {
        /*
        $media = Media::instance()->where('projectId',$vars['projectId'])->page($this->request->get('page', 1))->paginate(100);
        
        echo view('html.media.index', [
            'projectId' => $vars['projectId'],
            'media' => $media
        ]);
        */
    }

    public function upload(array $vars)
    {
        BladeOneCsrf::validate(true);

        $extension = $this->request->extension;
        $base64 = $this->request->image;

        if (!$extension) {
            echo response()->json(['success' => 0, 'error' => '拡張子がありません。'])->send();
        }

        $base64 = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $base64 = str_replace(' ', '+', $base64);
        $data = base64_decode($base64);
        $fileName = uniqid(). '.' . $extension;
        Media::instance()->setFile(
            'file',
            $fileName,
            $data
        )->create(
            [
                'fileName' => $fileName,
                'type' => $extension
            ]
        );

        echo response()->json(['success' => 1, 'url' => url('media.url',['filename' => $fileName])])->send();
    }

    public function fileUpload(array $vars)
    {
        BladeOneCsrf::validate(true);

        $extension = $this->request->extension;
        $base64 = $this->request->base64;

        if (!$extension) {
            echo response()->json(['success' => 0, 'error' => '拡張子がありません。'])->send();
        }

        $base64 = preg_replace('/^data:.*;base64,/', '', $base64);
        //$base64 = str_replace(' ', '+', $base64);
        $data = base64_decode($base64);
        $fileName = uniqid(). '.' . $extension;
        Media::instance()->setFile(
            'file',
            $fileName,
            $data
        )->create(
            [
                'fileName' => $fileName,
                'type' => $extension
            ]
        );
        $fileSize = strlen($data);

        echo response()->json(['success' => 1, 'file'=> [
            'url' => '%url/rel:mpgt:page_397396%&_path=file/'.$fileName,
            'name' => $fileName,
            'size' => $fileSize,
            'extension' => $extension,
            'title' => $fileName,
        ]
        ])->send();
    }
    public function fileUrl(array $vars)
    {
        $media = Media::instance()->where('fileName', $vars['filename'])->get()->first();
        echo '<meta http-equiv="refresh" content="0;URL='.$media->file.'">';
    }
    public function url(array $vars)
    {
        $media = Media::instance()->where('fileName', $vars['filename'])->get()->first();

        if($media->file){
            // CURLセッションを初期化
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $media->file);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // リダイレクトに追従
            $fileData = curl_exec($ch);
            // CURLセッションを閉じる
            if (curl_errno($ch)) {
                // CURLエラーのハンドリング
                echo response()->json(['error' => curl_error($ch)], 500)->send();
            }
        
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if ($httpcode != 200) {
                // HTTPステータスコードエラーのハンドリング
                echo response()->json(['error' => 'HTTP status code ' . $httpcode], 500)->send();
            }
            curl_close($ch);

            echo base64_encode($fileData);
        }
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
