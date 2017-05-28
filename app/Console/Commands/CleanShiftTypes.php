<?php

namespace Lara\Console\Commands;

use Illuminate\Console\Command;

use \Lara\ScheduleEntry;
use \Lara\JobType;

use Log;
use Symfony\Component\Console\Helper\ProgressBar;

class CleanShiftTypes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lara:clean-shifttypes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all ShiftTypes that are never used from the DB.';

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
        Log::info('Starting shift type cleanup...');
        $this->info('Starting shift type cleanup...');

        // Start counting time before processing every person
        $counterStart = microtime(true);

        // Retrieve all ShiftTypes
        $shifttypes = JobType::all();

        // Initialize progress bar
        $bar = $this->output->createProgressBar(count($shifttypes));

        // Iterate through all ShiftTypes, check if there is any ScheduleEntry it is used in, and if not - delete this ShiftType
        foreach ($shifttypes as $shifttype) {
            
            // Count corresponding ScheduleEntries
            $scheduleEntriesCount = ScheduleEntry::where('jbtyp_id', '=', $shifttype->id)->count();

            // First, check if this shift type is in use in any existing schedule and delete it if it's not
            if (  $scheduleEntriesCount === 0  ) {
                
                // Shift type is not used anywhere and can be remove without side effects
                
                // Log the action while we still have the data
                $this->info(''); // new line
                $this->info('Shift type deleted: "' . $shifttype->jbtyp_title . '" (id:' . $shifttype->id . '), it was not used in any schedule.');
                
                // Now delete the jobtype
                JobType::destroy($shifttype->id);
            }

            // Now check if there exists another shift with the same name and duration, and if so - delete this one, put id of that other shift in all ScheduleEntries.
            // TO BE ADDED

            // Adjust progress bar
            $bar->advance();
        }

        // Finish progress bar and end counter
        $bar->finish();
        $counterEnd = microtime(true);

        // Inform the users
        $this->info(''); // new line
        $this->info(''); // new line
        Log::info('Finished shift type cleanup after ' . ($counterEnd - $counterStart) . ' seconds.');
        $this->info('Finished shift type cleanup after ' . ($counterEnd - $counterStart) . ' seconds.');
    }
}
