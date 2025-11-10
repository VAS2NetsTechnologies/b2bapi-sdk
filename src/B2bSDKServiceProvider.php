<?php

// namespace VAS2Nets\B2b;

// use Illuminate\Support\ServiceProvider as BaseServiceProvider;

// class B2bServiceProvider extends BaseServiceProvider
// {
//     public function register()
//     {
//         $this->mergeConfigFrom(__DIR__ . '/Resources/config.php', 'B2b');
//         $this->app->singleton(B2b::class, function ($app) {
//             return new B2b('dev');
//         });
//     }

//     public function boot()
//     {
//         $this->publishes([
//             __DIR__ . '/Resources/config.php' => config_path('B2b.php')
//         ], 'config');
//     }
// }
