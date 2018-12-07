<?php

namespace Tomato\OmiPay;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Route;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OmiPay::class, function () {
            return new OmiPay($this->app);
        });

        $this->app->alias(OmiPay::class, 'tomato.omipay');

        $this->mergeConfigFrom(
            __DIR__.'/config/omipay.php', 'omipay'
        );
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/omipay.php' => config_path('omipay.php'),
        ]);

        if (Config::get('omipay.use_package_routes')) {
            Route::any("omipay/notification","Tomato\OmiPay\OmiPay@anyNotification");
        }
    }

    /**
     * Get the active router.
     *
     * @return Router
     */
    protected function getRouter()
    {
        return $this->app['router'];
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['tomato.omipay', OmiPay::class];
    }
}