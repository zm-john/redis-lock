<?php

namespace Quhang\RedisLock\Laravel;

use Quhang\RedisLock\Lock;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/lock.php' => config_path('lock.php'),
        ]);

        $this->app->singleton('lock', function () {
            $redis = app('redis')->connection(config('lock.connection'));
            return new Lock($redis);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
