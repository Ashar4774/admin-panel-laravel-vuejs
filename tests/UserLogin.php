<?php

namespace Tests;

use App\Models\User;

trait UserLogin
{
    public $user;

    public function setUpLogin(){
        // create user
        $this->user = User::factory()->create();
        // login
        $this->actingAs($this->user, 'sanctum');
    }
}
