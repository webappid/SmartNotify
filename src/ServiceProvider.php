<?php

namespace WebAppId\SmartNotify;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/sn.php', 'sn');
    }
}
