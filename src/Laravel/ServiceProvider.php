<?php

namespace Quhang\RedisLock\Laravel;

use Quhang\RedisLock\Lock;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->publishes([
            __DIR__.'/../../config/lock.php' => base_path('config'),
        ]);
    }

    public function boot()
    {
        $this->app->singleton('lock', function () {
            $redis = app('redis')->connection(config('lock.connection'));
            return new Lock($redis);
        });
    }
}
