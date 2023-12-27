<?php

namespace SpiralPress\App\Http\Controllers ;

use framework\Http\Controller;

class UrlFetchController extends Controller
{
    public function fetch(array $vars)
    {
        // cURLを使用してURLのコンテンツを取得
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->request->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $pageContent = curl_exec($ch);
        curl_close($ch);

        // メタデータの初期化
        $metaData = [
            'title' => '',
            'site_name' => '',
            'description' => '',
            'image' => ['url' => '']
        ];

        // ページコンテンツからメタデータを解析（正規表現を使用）
        preg_match('/<meta property="og:title" content="(.*?)"/', $pageContent, $matches);
        if ($matches) {
            $metaData['title'] = $matches[1];
        }

        preg_match('/<meta property="og:site_name" content="(.*?)"/', $pageContent, $matches);
        if ($matches) {
            $metaData['site_name'] = $matches[1];
        }

        preg_match('/<meta property="og:description" content="(.*?)"/', $pageContent, $matches);
        if ($matches) {
            $metaData['description'] = $matches[1];
        }

        preg_match('/<meta property="og:image" content="(.*?)"/', $pageContent, $matches);
        if ($matches) {
            $metaData['image']['url'] = $matches[1];
        }

        // JSONレスポンスを形成
        $response = [
            'success' => 1,
            'link' => $this->request->url,
            'meta' => $metaData
        ];

        echo json_encode($response);
    }
}