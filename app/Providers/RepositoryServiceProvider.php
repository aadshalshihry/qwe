<?php

namespace App\Providers;

use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\VariationRepository;
use App\Repositories\VariationValueRepository;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\ProductVariationRepository;
use App\Repositories\VariationRepositoryInterface;
use App\Repositories\VariationValueRepositoryInterface;
use App\Repositories\ProductVariationRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        // bind Product repository to the app
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        // bind Variation repository to the app
        $this->app->bind(VariationRepositoryInterface::class, VariationRepository::class);
        // bind VariationValue repository to the app
        $this->app->bind(VariationValueRepositoryInterface::class, VariationValueRepository::class);
        // bind ProductVariation repository to the app
        $this->app->bind(ProductVariationRepositoryInterface::class, ProductVariationRepository::class);
    }
}