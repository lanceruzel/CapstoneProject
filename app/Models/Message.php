<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'conversation_id',
        'content',
        'images',
    ];

    public function conversation(){
        return $this->belongsTo(Conversation::class);
    }

    public function account(){
        return $this->belongsTo(User::class);
    }
}
