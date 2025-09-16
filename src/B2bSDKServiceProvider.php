<?php

namespace VAS2Nets\B2bSDK;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class B2bSDKServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/Resources/config.php', 'B2bSDK');
        $this->app->singleton(B2bSDK::class, function ($app) {
            return new B2bSDK(
                config('B2bSDK.username'),
                config('B2bSDK.password'),
                config('B2bSDK.base_url')
            );
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/Resources/config.php' => config_path('B2bSDK.php')
        ], 'config');
    }
}
