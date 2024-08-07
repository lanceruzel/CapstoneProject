<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserShippingInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'address_1',
        'address_2',
        'postal',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
