<?php

namespace App\Providers;

use App\Repositories\ImageRepository;
use App\Repositories\Interfaces\ImageRepositoryInterface;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Repositories\PostRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // NOTE: Every time Laravel needs an instance of PostRepositoryInterface, it creates and provides an instance of PostRepository.
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(ImageRepositoryInterface::class, ImageRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
