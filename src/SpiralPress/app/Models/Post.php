<?php

namespace App\Models;

use framework\Exception\NotFoundException;
use framework\SpiralConnecter\SpiralModel;

class Post extends SpiralModel {

    protected string $primaryKey = 'postId';

    protected array $fields = [
        'id', 'createAt', 'updateAt' , 'postId', 'categoryShareId', 'projectId', 'title', 'jsonContents' , 'postShareId'
    ];

    protected string $db_title = 'sp_posts';

    protected $manager = null;

    public function __construct (){}

    public static function allByProjectId($projectId){
        /** @phpstan-ignore-next-line */
        $instance = new static();
        $data = $instance->getManager()->orderBy('id' , 'asc')->where('projectId',$projectId)->get();

        $models = [];

        if ($data) {
            foreach ($data as $d) {
                // データベースから取得したデータを使用して新しいインスタンスを作
                /** @phpstan-ignore-next-line */
                $modelInstance = new static();

                // 新しいインスタンスの各プロパティにデータを設定
                foreach ($d as $key => $value) {
                    $modelInstance->$key = $value;
                }

                $models[] = $modelInstance;
            }
        }

        return $models;
    }
    public static function allByProjectIdAndCategoryShareId($projectId , $categoryShareId){
        /** @phpstan-ignore-next-line */
        $instance = new static();
        $data = $instance->getManager()->orderBy('id' , 'asc')->where('projectId',$projectId)->where('categoryShareId' , $categoryShareId)->get();

        $models = [];

        if ($data) {
            foreach ($data as $d) {
                // データベースから取得したデータを使用して新しいインスタンスを作
                /** @phpstan-ignore-next-line */
                $modelInstance = new static();

                // 新しいインスタンスの各プロパティにデータを設定
                foreach ($d as $key => $value) {
                    $modelInstance->$key = $value;
                }

                $models[] = $modelInstance;
            }
        }

        return $models;
    }
    public static function findByPostIdAndProjectId($postId, $projectId){
        /** @phpstan-ignore-next-line */
        $instance = new static();
        $data = $instance->getManager()->orderBy('id' , 'asc')->where('postId', $postId)->where('projectId',$projectId)->get();

        $model = null;

        if ($data->first()) {
            /** @phpstan-ignore-next-line */
            $model = new static();

            // 新しいインスタンスの各プロパティにデータを設定
            foreach ($data->first() as $key => $value) {
                $model->$key = $value;
            }

        }

        if($model === null){
            throw new NotFoundException('Not Found', 404);
        }

        return $model;
    }
    public static function findByPostShareIdAndProjectId($postShareId, $projectId){
        /** @phpstan-ignore-next-line */
        $instance = new static();
        $data = $instance->getManager()->orderBy('id' , 'asc')->where('postShareId', $postShareId)->where('projectId',$projectId)->get();

        $model = null;

        if ($data->first()) {
            /** @phpstan-ignore-next-line */
            $model = new static();

            // 新しいインスタンスの各プロパティにデータを設定
            foreach ($data->first() as $key => $value) {
                $model->$key = $value;
            }

        }

        if($model === null){
            throw new NotFoundException('Not Found', 404);
        }

        return $model;
    }

    public function saveWithJsonFile($json){
        $instance = $this;
        unset($instance->jsonContents);
        $instance->getManager()->setFile('jsonContents','content.json',$json);
        return $instance->save();
    }
}