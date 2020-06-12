<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

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

        $this->mapWebRoutes();

        $this->mapAbyssUserRoutes();

        $this->mapAffiliateRoutes();

        $this->mapSellerRoutes();

        $this->mapAdminRoutes();

        //
    }    
    
    /**
     * Define the "admin" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::prefix('admin')
             ->middleware(['web'])
             ->namespace($this->namespace)
             ->group(base_path('routes/admin.php'));
    }    
    
    /**
     * Define the "seller" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapSellerRoutes()
    {
        Route::prefix('seller')
             ->middleware(['web'])
             ->namespace($this->namespace)
             ->group(base_path('routes/seller.php'));
    }    
    
    /**
     * Define the "affiliate" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAffiliateRoutes()
    {
        Route::prefix('affiliate')
             ->middleware(['web'])
             ->namespace($this->namespace)
             ->group(base_path('routes/affiliate.php'));
    }    
    
    /**
     * Define the "abyss_user" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAbyssUserRoutes()
    {
        Route::prefix('abyssuser')
             ->middleware(['web'])
             ->namespace($this->namespace)
             ->group(base_path('routes/abyssuser.php'));
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
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }
}
