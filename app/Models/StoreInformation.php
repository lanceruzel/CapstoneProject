<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'contact',
        'country',
        'state',
        'requirements',
        'profile_bio',
        'profile_picture',
        'paypal_merchant_id',
        'paypal_email'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
