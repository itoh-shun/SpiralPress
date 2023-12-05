<?php

namespace SpiralPress;

use framework\Application;
use framework\SpiralConnecter\RateLimiter;
use framework\SpiralConnecter\SpiralDB;

class SpiralPressApplication extends Application
{
    public function __construct()
    {
        config_path("SpiralPress/config/app");
        parent::__construct();
    }

    public function boot()
    {
        RateLimiter::setLimit(3000);
        SpiralDB::setToken(config('system_token.token',''), config('system_token.secret',''));
    }
}
