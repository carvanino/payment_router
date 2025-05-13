<?php

namespace Akinola\PaymentRouter;

use Illuminate\Support\ServiceProvider;
use Akinola\PaymentRouter\Contracts\PaymentRouterInterface;
use Akinola\PaymentRouter\Services\PaymentRouter;

class PaymentRouterServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Merge package configuration with the application's published copy
        $this->mergeConfigFrom(
            __DIR__.'/../config/payment-router.php', 'payment-router'
        );

        // Register the main service
        $this->app->singleton(PaymentRouterInterface::class, function ($app) {
            return new PaymentRouter($app);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Publish configuration file
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/payment-router.php' => config_path('payment-router.php'),
            ], 'config');
        }
    }
}