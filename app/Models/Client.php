<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'ref_no',
        'name'
    ];

    public function invoices(){
        return $this->hasMany(Invoice::class, 'clients_id');
    }
}
