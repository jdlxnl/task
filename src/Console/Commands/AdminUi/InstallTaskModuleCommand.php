<?php

namespace Jdlx\Task\Console\Commands\AdminUi;

use Illuminate\Support\Facades\File;
use Jdlx\Task\Models\TaskLog;
use Jdlx\Task\Models\TaskLogEntry;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InstallTaskModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adminui:module:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the task module in the admin ui';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $path = base_path()."/webclient";
        $modulePath = base_path()."/webclient/src/module";
        if(!file_exists($path)){
            $this->output->error("Could not find webclient in base path");
            return -1;
        }

        if(!file_exists($modulePath)){
            mkdir($modulePath, 0777, true);
        }

        $src = dirname(dirname(dirname(dirname(dirname((__DIR__))))). "/publish/webclient/task");
        File::copyDirectory($src, $modulePath);
        chdir($path);
        exec("npm install src/module/task");

        return 0;
    }
}
