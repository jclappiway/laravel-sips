<?php

namespace Jclappiway\LaravelSips\Providers;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    use AppNamespaceDetectorTrait;

    public function register()
    {
        $this->app->bindIf(
            'LaravelSipsOrderObserver', function () {
                return new \Jclappiway\LaravelSips\Observers\OrderObserver;
            }
        );

        $this->app->bindIf(
            'LaravelSipsTransactionObserver', function () {
                return new \Jclappiway\LaravelSips\Observers\TransactionObserver;
            }
        );

        $this->app->bindIf(
            'LaravelSipsNotifier', function () {
                return new \Jclappiway\LaravelSips\Models\Notifier;
            }
        );

    }

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->loadViewsFrom(__DIR__ . '/../views', 'jclappiway.laravel-sips');
        $this->loadTranslationsFrom(__DIR__ . '/../langs', 'jclappiway.laravel-sips');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../config.php' => config_path('sips.php'),
            __DIR__ . '/../langs'      => base_path('resources/lang/vendor/jclappiway.laravel-sips'),
            __DIR__ . '/../views'      => base_path('resources/views/vendor/jclappiway.laravel-sips'),
        ]);
    }

}
