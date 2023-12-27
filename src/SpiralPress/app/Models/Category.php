<?php

namespace App\Models;

use framework\Exception\NotFoundException;
use framework\SpiralConnecter\SpiralModel;

class Category extends SpiralModel {

    protected string $primaryKey = 'categoryId';

    protected array $fields = [
        'id', 'createAt', 'updateAt' , 'categoryId', 'categoryName' , 'projectId' , 'categoryShareId'
    ];

    protected string $db_title = 'sp_category';

    protected $manager = null;

    public function __construct (){}

    public static function findByCategoryIdAndProjectId($categoryId, $projectId){
        /** @phpstan-ignore-next-line */
        $instance = new static();
        $data = $instance->getManager()->orderBy('id' , 'asc')->where('categoryId', $categoryId)->where('projectId',$projectId)->get();

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
}