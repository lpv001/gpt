<?php

namespace App\Frontend\Providers;

use Illuminate\Support\ServiceProvider;



class FrontendServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \View::addNamespace('frontend', __DIR__.'/../resources/views/');
    }
}
