<?php
global $arry;

return [
    "debug" => false,
    "name" => "SpiralPress",
    "timezone" => "Asia/Tokyo",
    "locale" => "ja",
    'copyright' => 'itoshun',
    'url' => [
        'logout' => '%form:act:logout%',
    ],
    'system_token' => [
        'token' => defined('TOKEN') ? TOKEN : '',
        'secret' => defined('SECRET') ? SECRET : ''
    ]
];
