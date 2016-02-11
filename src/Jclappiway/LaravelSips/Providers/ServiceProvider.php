<?php

namespace Jclappiway\LaravelSips\Providers;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    use AppNamespaceDetectorTrait;

    public function register()
    {
    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

}
