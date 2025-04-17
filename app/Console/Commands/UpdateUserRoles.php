<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class UpdateUserRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:user-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update user roles';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Assign role 'Admin' to user with ID 1
        $adminUser = User::find(1);
        if ($adminUser) {
            $adminUser->role = 'Admin';
            $adminUser->save();
        }

        // Assign role 'Helpdesk' to user with ID 2
        $helpdeskUser = User::find(2);
        if ($helpdeskUser) {
            $helpdeskUser->role = 'Helpdesk';
            $helpdeskUser->save();
        }

        $this->info('User roles updated successfully!');
    }
}