<?php

namespace App\Providers;

use App\Interfaces\Product\ProductRepositoryInterface;
use App\Interfaces\StripePayment\PaymentRepositoryInterface;
use App\Repository\Product\ProductRepository;
use App\Repository\StripePayment\PaymentRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(PaymentRepositoryInterface::class, PaymentRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
