<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\CodeData;

class GPTCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gpt:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'GPT CronJob Command';

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
     * @return mixed
     */
    public function handle()
    {
        $coder = new CodeData();
        $id = 0;
        $is_cron = 1;
        $is_debug = 0;
        $coder->runCodeGenerator($id, $is_cron, $is_debug);
        echo "job is working";
    } // EOF
}
