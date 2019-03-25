<?php

namespace Lara\Console\Commands;

use Illuminate\Console\Command;
use Lara\User;

class ResetUserPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lara:resetUserPassword {username}';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    
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
        $search = $this->argument('username');
        $users = User::query()->where('name', '=', $search)->get();
        $this->info('candidates:');
        foreach ($users as $index => $user) {
            /** @var User $user */
            $this->info('['.$index.'] '.$user->name.' ('.$user->givenname.', '.$user->lastname.') '.$user->email.' '.$user->section->title);
        }
        $choice = $this->ask('which user do you want to reset? -1 is exit');
        if ($choice == -1) {
            return 0;
        }
        if ($choice > $users->count()) {
            $this->info('invalid choice, max available: 0-'.($users->count() - 1).' chosen: '.$choice);
            
            return -1;
        }
        $user = $users->get(intval($choice));
        $newPassword = uniqid();
        $user->password = bcrypt($newPassword);
        $user->save();
        
        $this->info('New Password: '.$newPassword);
        
        return 0;
    }
}
