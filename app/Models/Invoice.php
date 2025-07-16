<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'clients_id',
        'due_date',
        'amount',
        'rcd_due_date',
        'rcd_amount',
        'bad_debt_amount',
        'invoice_year',
        'time_gap',
        'notes',
        'status',
        'payment_type'
    ];

    protected $appends = [ 'formatted_due_date', 'formatted_rcd_due_date', 'time_gap'];

    public function clients(){
        return $this->belongsTo(Client::class, 'clients_id');
    }

    public function getFormattedDueDateAttribute()
    {
        return Carbon::parse($this->due_date)->format('d-m-y');
    }

    public function getFormattedRcdDueDateAttribute()
    {
        return Carbon::parse($this->rcd_due_date)->format('d-m-y');
    }

    public function getTimeGapAttribute(){
        $dueDate = Carbon::parse($this->due_date);
        $rcdDueDate = Carbon::parse($this->rcd_due_date);

        return round($dueDate->diffInDays($rcdDueDate));
    }
}
