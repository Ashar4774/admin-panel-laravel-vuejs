<?php

namespace App\Policies\v1;

use App\Models\User;

class StateOfAccountPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user){
        return $user->hasPermission('view-state-of-account');
    }

    public function export(User $user){
        return $user->hasPermission('export-state-of-account');
    }
}
