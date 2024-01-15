<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';


    protected $webNamespace = 'App\Frontend';

    protected $adminNamespace = 'App\Admin';
    //protected $apiNamespace = 'App\Http\Controllers\Api';
    protected $apiNamespace = 'App\API';



    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapAdminRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::domain(env('DOMAIN_FRONTEND'))
             ->middleware('web')
             //->namespace($this->namespace)
             ->namespace($this->webNamespace)
             //->group(base_path('routes/web.php'));
             ->group(base_path('app/Frontend/routes.php'));
    }



    protected function mapAdminRoutes()
    {
        Route::domain(env('DOMAIN_ADMIN'))
             ->middleware('web')
             //->namespace($this->namespace)
             ->namespace($this->adminNamespace)
             //->group(base_path('routes/web.php'));
             ->group(base_path('app/Admin/routes.php'));
    }




    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    /*protected function mapApiRoutes()
    {
        Route::domain('api.gangos2.test')->prefix('api')
             ->middleware('api')
             ->namespace('App\Frontend')
             ->group(base_path('routes/api.php'));
    }
    */

    protected function mapApiRoutes()
    {
        Route::domain(env('DOMAIN_API','api.gangos2.test'))->prefix('api')
             ->middleware('api')
             ->as('api.')
             ->namespace($this->apiNamespace)
             ->group(base_path('app/API/routes.php'));
    }



}
