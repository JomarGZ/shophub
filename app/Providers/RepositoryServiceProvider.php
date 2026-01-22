<?php

namespace App\Providers;

use App\Repositories\Decorators\CachedProductRepository;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {   if (app()->environment('local', 'testing')) {
            $this->app->bind(
                ProductRepositoryInterface::class,
                ProductRepository::class
            );
        } else {
            $this->app->bind(ProductRepositoryInterface::class, function ($app) {
                return new CachedProductRepository($app->make(ProductRepository::class));
            });
        }
       
        $this->app->bind(\App\Repositories\Contracts\OrderRepositoryInterface::class, \App\Repositories\Eloquent\OrderRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
