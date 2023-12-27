<?php

namespace App\Models;
use framework\SpiralConnecter\SpiralModel;

class Media extends SpiralModel {

    protected string $primaryKey = 'id';

    protected array $fields = [
        'id', 'createAt', 'updateAt' , 'file' , 'fileName' , 'type'
    ];

    protected string $db_title = 'sp_media';

    protected $manager = null;

    public function __construct (){}

}