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

    public function calculateArrears(){
        return $this->invoices->sum('amount') - $this->invoices->sum('rcd_amount');
    }

    public function calculateBadDebts(){
        return $this->invoices->where('status', 'bad_debts')->sum('bad_debt_amount');
    }
}
