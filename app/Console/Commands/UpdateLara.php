<?php

namespace Lara\Console\Commands;

use Illuminate\Console\Command;
use Log;

class UpdateLara extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lara:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull latests changes from assigned branch, clear cache and views and apply migrations.';

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
        // Inform the users
        $this->info(''); // new line
        Log::info('Starting Lara update...');
        $this->info('Starting Lara update...');

        // start counting time before processing every person
        $counterStart = microtime(true);

        // List of instructions to execute
        $instructions = [
            'php artisan down',             // Enter maintenance mode
            'git pull',                     // Download latest changes from GitHub
            'composer install',             // Install and update dependencies
            'php artisan view:clear',       // Clear and update cache
            'php artisan config:cache',
            'npm install',                  // JavaScript/TypeScript deployment
            'npm run dev',
            'php artisan migrate',          // Apply new database changes
            'php artisan up'                // Exit maintenance mode
        ];

        // initialize progress bar
        $bar = $this->output->createProgressBar(count($instructions));

        // perform the update
        foreach ($instructions as $step) {

            // log what you are doing
            $this->info(''); // new line
            $this->info('Executing "' . $step . '"...');

            // perform the instruction
            exec($step);

            // adjust progress bar
            $bar->advance();
            $this->info(''); // new line
        }

        // finish progress bar and end counter
        $bar->finish();
        $counterEnd = microtime(true);

        // Inform the users
        $this->info(''); // new line
        $this->info(''); // new line
        Log::info('Finished Lara update after ' . ($counterEnd - $counterStart) . ' seconds.');
        $this->info('Finished Lara update after ' . ($counterEnd - $counterStart) . ' seconds.');

    }
}
