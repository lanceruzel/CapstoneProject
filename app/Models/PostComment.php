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

    public function getDateTimeDiff(){
        $seconds_ago = (time() - strtotime($this->created_at));
        $dateTimeDisplay = '';

        if ($seconds_ago >= 31536000) {
            $dateTimeDisplay = intval($seconds_ago / 31536000) . " years ago";
        } elseif ($seconds_ago >= 2419200) {
            $dateTimeDisplay = intval($seconds_ago / 2419200) . " months ago";
        } elseif ($seconds_ago >= 86400) {
            $dateTimeDisplay = intval($seconds_ago / 86400) . " days ago";
        } elseif ($seconds_ago >= 3600) {
            $dateTimeDisplay = intval($seconds_ago / 3600) . " hours ago";
        } elseif ($seconds_ago >= 120) {
            $dateTimeDisplay = intval($seconds_ago / 60) . " minutes ago";
        } elseif ($seconds_ago >= 60) {
            $dateTimeDisplay = "1 minute ago";
        } else {
            $dateTimeDisplay = "Less than a minute ago";
        }

        return $dateTimeDisplay;
    }
}
