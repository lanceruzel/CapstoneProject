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

    public function getConversations(){
        return Conversation::where(function($query) {
            $query->where('user_1', '<>', 1)
                  ->where('user_2', '<>', 1)
                  ->where(function($query) {
                      $query->where('user_1', Auth::id())
                            ->orWhere('user_2', Auth::id());
                  });})
                  ->whereNotNull('last_message_id')
                  ->orderBy('updated_at', 'desc')
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
