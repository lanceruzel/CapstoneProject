<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'post_id',
        'content',
        'livestream_id'
    ];

    public function post(){
        return $this->belongsTo(Post::class);
    }

    public function livestream(){
        if($this->livestream_id){
            return $this->belongsTo(Livestream::class);
        }
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
