<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'requested_to',
        'account_name',
        'paypal_email',
        'amount',
        'currency',
        'reference_id',
        'status',
    ];

    public function requestedTo(){
        return $this->belongsTo(User::class, 'requested_to');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
