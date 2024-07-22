<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livestream extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'conversation_id',
        'title',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function conversation(){
        return $this->hasOne(Conversation::class);
    }
}
