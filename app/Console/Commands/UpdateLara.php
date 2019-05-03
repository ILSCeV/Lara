<?php

namespace Lara\Console\Commands;

use Illuminate\Console\Command;
use Lara\utilities\CacheUtility;
use Log;

class UpdateLara extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lara:update {--server-mode}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enter maintenance mode, pull latests changes from assigned branch, clear cache and views, apply migrations, go live again.';

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

        $serverMode = $this->option('server-mode');
        if ($serverMode) {
            $avoidSourseConflictCommand = 'git reset --hard';
        } else {
            $avoidSourseConflictCommand = 'git stash';
        }
        if(!$serverMode){
            $reApplyChangesCommand = 'git stash pop || true';
        } else {
            $reApplyChangesCommand = '# nothing to do';
        }

        // start counting time before processing every person
        $counterStart = microtime(true);

        // List of instructions to execute
        $instructions = [
            'php artisan down',
            // Enter maintenance mode
            $avoidSourseConflictCommand,
            // reset repo to avoid conflicts
            'git pull --rebase',
            // Download latest changes from GitHub
            'rm composer.lock || true',
            // remove composer.lock, makes sure that you will get the stuff from composer.json
            'rm package-lock.json || true',
            // remove package-lock, makes sure that you will get the stuff from package.json
            'sh git-create-revisioninfo-hook.sh',
            // Update version info in the footer
            'composer install',
            // Install and update dependencies
            'php artisan view:clear',
            // Clear and update cache
            'php artisan config:cache',
            'npm install',
            // JavaScript/TypeScript deployment
            'npm run production',
            'php artisan migrate --force',
            $reApplyChangesCommand,
            // Apply new database changes
            'php artisan up'
            // Exit maintenance mode
        ];

        // initialize progress bar
        $bar = $this->output->createProgressBar(count($instructions));

        // perform the update
        foreach ($instructions as $step) {

            // log what you are doing
            $this->info(''); // new line
            $this->info('Executing "' . $step . '"...');

            // perform the instruction
            passthru($step, $result);
            $this->info('result: '.$result);
            if ($result != 0) {
                return $result;
            }
            // adjust progress bar
            $bar->advance();
            $this->info(''); // new line
        }

        // finish progress bar and end counter
        $bar->finish();
        CacheUtility::clear();
        $counterEnd = microtime(true);

        // Inform the users
        $this->info(''); // new line
        $this->info(''); // new line
        Log::info('Finished Lara update after ' . ($counterEnd - $counterStart) . ' seconds.');
        $this->info('Finished Lara update after ' . ($counterEnd - $counterStart) . ' seconds.');

    }
}
