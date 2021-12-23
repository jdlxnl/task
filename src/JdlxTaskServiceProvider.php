<?php

namespace Jdlx\Task;

use Illuminate\Support\ServiceProvider;
use Jdlx\Task\Console\Commands\TaskLog\ClearTaskLogCommand;
use Jdlx\Task\Console\Commands\TaskLog\ListTaskLogCommand;
use Jdlx\Task\Console\Commands\TaskLog\ViewTaskLogCommand;

class JdlxTaskServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../publish/public' => base_path('public'),
            __DIR__ . '/../publish/resources' => base_path('resources'),
            __DIR__ . '/../publish/migration' => base_path('database/migrations'),
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                ClearTaskLogCommand::class,
                ListTaskLogCommand::class,
                ViewTaskLogCommand::class
            ]);
        }
        //$this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }


}
