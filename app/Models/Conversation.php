<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_1',
        'user_2',
        'status',
        'last_message_id'
    ];

    public function lastMessage(){
        return $this->belongsTo(Message::class, 'last_message_id');
    }

    public function messages(){
        return $this->hasMany(Message::class);
    }

    public function user1(){
        return $this->belongsTo(User::class, 'user_1');
    }

    //Receiver
    public function user2(){
        return $this->belongsTo(User::class, 'user_2');
    }

    public function appeal(){
        return $this->hasOne(ReportAppeal::class);
    }

    public function livestream(){
        return $this->belongsTo(Livestream::class);
    }
}
