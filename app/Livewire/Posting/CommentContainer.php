<?php

namespace App\Livewire\Posting;

use Livewire\Component;

class CommentContainer extends Component
{
    public $comment;

    public function mount($comment){
        $this->comment = $comment;
    }

    public function render()
    {
        return view('livewire.Posting.comment-container', [
            'comment' => $this->comment
        ]);
    }
}
