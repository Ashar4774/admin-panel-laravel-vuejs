<?php

namespace App\Policies\v1;

use App\Models\User;
use App\Models\Invoice;

class InvoicePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function view(User $user){
        return $user->hasPermission('view-invoice');
    }

    public function create(User $user){
        return $user->hasPermission('create-invoice');
    }

    public function update(User $user, Invoice $invoice){
        return $user->hasPermission('update-invoice');
    }

    public function delete(User $user, Invoice $invoice){
        return $user->hasPermission('delete-invoice');
    }

    public function import(User $user, Invoice $invoice){
        return $user->hasPermission('import-invoice');
    }
}
