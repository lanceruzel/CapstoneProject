<?php

namespace App\Livewire\Posting;

use App\Enums\Status;
use App\Models\Post;
use Livewire\Component;

class PostsContainer extends Component
{
    protected $listeners = [
        'post-created' => '$refresh'
    ];

    public function render()
    {
        return view('livewire.Posting.posts-container', [
            'posts' => Post::where('status', Status::Available)->orderBy('created_at', 'desc')->get()
        ]);
    }
}
