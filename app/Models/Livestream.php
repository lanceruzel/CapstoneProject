<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livestream extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'title',
        'status',
        'reactions',
        'location',
    ];

    public function getReactionCount($reaction){
        $reactions = json_decode($this->reactions, true); 
        return isset($reactions[$reaction]) ? $reactions[$reaction]['count'] : 0;
    }

    public function incrementReactionCount($reaction) {
        $reactions = json_decode($this->reactions, true); // Decode as associative array
    
        if (isset($reactions[$reaction])) {
            $reactions[$reaction]['count'] += 1;
        } else {
            // Initialize the reaction if it doesn't exist
            $reactions[$reaction] = ['count' => 1];
        }
    
        $this->reactions = json_encode($reactions); // Update the reactions JSON
        $this->save(); // Save the changes if using a model
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function postComments(){
        return $this->hasMany(PostComment::class);
    }
}
