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
    {
        $this->app->bind(ProductRepositoryInterface::class, function ($app) {
            $repository = new ProductRepository;

            return new CachedProductRepository($repository);
        });

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
