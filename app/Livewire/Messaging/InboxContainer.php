<?php

namespace App\Livewire\Messaging;

use App\Models\Conversation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InboxContainer extends Component
{
    public $userID;
    public $search;
    
    public function getListeners(){
        return [
            "echo:new-chat.{$this->userID},NewChatCreated" => '$refresh',
        ];
    }

    public function mount(){
        $this->userID = Auth::id();
    }

    public function getDateTimeDiff($datetime){
        $seconds_ago = (time() - strtotime($datetime));
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

    public function getConversations(){
        return Conversation::where(function($query) {
            $query->where('user_1', '<>', 1)
                  ->where('user_2', '<>', 1)
                  ->where(function($query) {
                      $query->where('user_1', Auth::id())
                            ->orWhere('user_2', Auth::id());
                  });})
                  ->whereNotNull('last_message_id')
                  ->get();
    }

    public function render()
    {
        $this->dispatch('messagesUpdated');
        
        return view('livewire.Messaging.inbox-container', [
            'conversations' => $this->getConversations(),
        ]);
    }
}
