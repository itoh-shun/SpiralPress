<?php

namespace App\Models;
use framework\SpiralConnecter\SpiralModel;

class Project extends SpiralModel {

    protected string $primaryKey = 'projectId';

    protected array $fields = [
        'createAt', 'updateAt' , 'authKey' , 'projectName' , 'projectId' , 'globalMenuJson', 'topPage' , 'theme'
    ];

    protected string $db_title = 'sp_project';

    protected $manager = null;

    public function __construct (){}

}