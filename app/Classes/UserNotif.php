<?php

namespace App\Classes;

use App\Events\NotificationCreated;
use App\Models\Notification;

class UserNotif{
    public function __construct(){
        //
    }

    public static function sendNotif($userID, $content, $type){
        $notif = Notification::create([
            'user_id' => $userID,
            'type' => $type,
            'content' => $content,
            'status' => 'unread',
        ]);

        if($notif){
            NotificationCreated::dispatch($userID);
        }
    }
}
