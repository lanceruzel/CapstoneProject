<?php

namespace App\Livewire\Livestream;

use App\Events\LivestreamChatCreated;
use App\Models\PostComment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LivestreamChatContainer extends Component
{
    public $meetingId;
    public $content;
    public $comments = [];

    public function getListeners(){
        return [
            "echo:new-livestream-comment.{$this->meetingId},LivestreamChatCreated" => '$refresh',
        ];
    }

    public function mount($meetingId){
        $this->meetingId = $meetingId;
    }

    public function sendMessage(){
        $validated = $this->validate(['content' => 'required']);

        if($this->meetingId){
            $postComment = PostComment::create([
                'livestream_id' => $this->meetingId,
                'user_id' => Auth::id(),
                'content' => $validated['content']
            ]);

            if($postComment){
                LivestreamChatCreated::dispatch($postComment, $this->meetingId);
                $this->reset('content');
            }
        }
    }

    public function render(){
        if($this->meetingId){
            $this->comments = PostComment::where('livestream_id', $this->meetingId)->get();
        }

        return view('livewire.Livestream.livestream-chat-container');
    }
}
