<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Product Repository
        $this->app->bind(
            'App\Repositories\Contracts\ProductRepositoryContract', 
            'App\Repositories\Eloquent\EloquentProductRepository'
        );

        // Coupon Repository
        $this->app->bind(
            'App\Repositories\Contracts\CouponRepositoryContract', 
            'App\Repositories\Eloquent\EloquentCouponRepository'
        );

        // Cart Repository
        $this->app->bind(
            'App\Repositories\Contracts\CartRepositoryContract', 
            'App\Repositories\Session\SessionCartRepository'
        );
    }
}
