<?php

namespace App\Livewire\Livestream;

use App\Events\LiveReactionCreated;
use App\Models\Livestream;
use App\Models\PostComment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Events\LivestreamChatCreated;

class LivestreamReactionContainer extends Component
{
    public $livestream;
    public $meetingId;
    public $role;

    public function mount($meetingId, $role = 'viewer'){
        $this->meetingId = $meetingId;
        $this->role = $role;
        $this->livestream = Livestream::where('id', $meetingId)->first();
    }

    public function sendReaction($reaction){
        $formatted = '0';

        switch($reaction){
            case '1':
                $formatted = '&#128525;';
                break;
            case '2':
                $formatted = '&#128514;';
                break;
            case '3':
                $formatted = '&#128545;';
                break;
            case '4':
                $formatted = '&#128558;';
                break;

            default:
                $formatted = '&#128525;';
                break;
        }

        // $postComment = PostComment::create([
        //     'livestream_id' => $this->meetingId,
        //     'user_id' => Auth::id(),
        //     'content' => 'Reacted: '. $formatted
        // ]);

        // if($postComment){
            // LivestreamChatCreated::dispatch($postComment, $this->meetingId);
            LiveReactionCreated::dispatch($formatted, $this->meetingId);
        // }

        if($this->livestream){
            $this->livestream->incrementReactionCount($reaction);
        }
    }

    public function render()
    {
        return view('livewire.Livestream.livestream-reaction-container');
    }
}
