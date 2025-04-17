<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserRoleController extends Controller
{
    public function updateRoles()
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

        return 'Roles updated successfully!';
    }
}