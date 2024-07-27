<?php

namespace App\Classes;

use App\Events\NotificationCreated;
use App\Models\Notification;

class UserNotif{
    public function __construct(){
        //
    }

    public static function sendNotif($userID, $content){
        $notif = Notification::create([
            'user_id' => $userID,
            'type' => 'status',
            'content' => $content,
            'status' => 'unread',
        ]);

        if($notif){
            NotificationCreated::dispatch($userID);
        }
    }
}
