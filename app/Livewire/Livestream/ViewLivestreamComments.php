<?php

namespace App\Livewire\Livestream;

use App\Models\PostComment;
use Livewire\Component;

class ViewLivestreamComments extends Component
{
    public $meetingId;
    public $comments = [];

    protected $listeners = [
        'view-livestream-comments' => 'getData',
        'clearViewLivestreamCommentsModalData' => 'clearData'
    ];

    public function getData($meetingId){
        $this->meetingId = $meetingId;
    }

    public function clearData(){
        $this->reset([
            'meetingId',
            'comments'
        ]);
    }

    public function render(){
        if($this->meetingId){
            $this->comments = PostComment::where('livestream_id', $this->meetingId)->get();
        }

        return view('livewire.Livestream.view-livestream-comments');
    }
}
