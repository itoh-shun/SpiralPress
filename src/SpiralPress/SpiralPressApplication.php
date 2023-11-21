<?php

namespace SpiralPress;

use framework\Application;

class SpiralPressApplication extends Application
{
    public function __construct()
    {
        config_path("SpiralPress/config/app");
        parent::__construct();
    }

    public function boot()
    {
    }
}
