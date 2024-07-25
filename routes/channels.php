<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('new-comment', function (){
    return true;
});

Broadcast::channel('new-livestream-comment', function (){
    return true;
});

Broadcast::channel('post-updated', function (){
    return true;
});

Broadcast::channel('new-chat', function ($userID, $id){
    return (int) $userID === (int) $id;
});