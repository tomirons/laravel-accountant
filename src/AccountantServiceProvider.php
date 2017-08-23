<?php

namespace TomIrons\Accountant;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AccountantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerResources();
        $this->defineAssetPublishing();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/accountant.php', 'accountant'
        );

    }

    /**
     * Register the Accountant routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => 'accountant',
            'namespace' => 'TomIrons\Accountant\Http\Controllers',
            'middleware' => 'web',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        });
    }

    /**
     * Register the Accountant resources.
     *
     * @return void
     */
    protected function registerResources()
    {
//        $this->loadViewsFrom(__DIR__.'/../resources/views', 'accountant');
    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    public function defineAssetPublishing()
    {
//        $this->publishes([
//            __DIR__.'/../public/js' => public_path('vendor/accountant/js'),
//        ], 'accountant-assets');
//
//        $this->publishes([
//            __DIR__.'/../public/css' => public_path('vendor/accountant/css'),
//        ], 'accountant-assets');
//
//        $this->publishes([
//            __DIR__.'/../public/img' => public_path('vendor/accountant/img'),
//        ], 'accountant-assets');
    }
}
