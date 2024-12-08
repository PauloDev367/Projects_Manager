<?php

namespace App\Providers;

use App\Repositories\Ports\IProjectRepository;
use App\Repositories\ProjectRepository;
use App\Services\ProjectsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProjectsService::class);
        $this->app->bind(IProjectRepository::class, ProjectRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
