<?php

namespace Lara\Console\Commands;

use Illuminate\Console\Command;

use Log;

class TestLog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testlog';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Puts an entry into the log file if executed successfully.';

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
        Log::warning('TestLog command executed successfully.');
        $this->info('TestLog executed successfully, check the log file for a new entry.');
    }
}
