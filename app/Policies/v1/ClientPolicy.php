<?php

namespace App\Policies\v1;

use App\Models\User;
use App\Models\Client;

class ClientPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user){
        return $user->hasPermission('view-client');
    }

    public function create(User $user){
        return $user->hasPermission('create-client');
    }

    public function update(User $user, Client $client){
        return $user->hasPermission('update-client');
    }

    public function delete(User $user, Client $client){
        return $user->hasPermission('delete-client');
    }

    public function import(User $user, Client $client){
        return $user->hasPermission('import-client');
    }
}
