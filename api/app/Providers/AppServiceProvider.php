<?php

namespace App\Providers;

use App\Repositories\ColumnRepository;
use App\Repositories\Ports\IColumnRepository;
use App\Repositories\Ports\IProjectRepository;
use App\Repositories\Ports\ITaskToDoRepository;
use App\Repositories\Ports\ITicketRepository;
use App\Repositories\ProjectRepository;
use App\Repositories\TaskToDoRepository;
use App\Repositories\TicketRepository;
use App\Services\ColumnsService;
use App\Services\ProjectsService;
use App\Services\TaskToDoService;
use App\Services\TicketService;
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
        $this->app->bind(ColumnsService::class);
        $this->app->bind(IColumnRepository::class, ColumnRepository::class);
        $this->app->bind(TaskToDoService::class);
        $this->app->bind(ITaskToDoRepository::class, TaskToDoRepository::class);
        $this->app->bind(TicketService::class);
        $this->app->bind(ITicketRepository::class, TicketRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
