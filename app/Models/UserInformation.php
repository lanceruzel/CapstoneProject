<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'birthdate',
        'country',
        'address',
        'profile_bio',
        'profile_picture',
    ];

    public function fullName(){
        return $this->first_name . ' ' . $this->last_name;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
