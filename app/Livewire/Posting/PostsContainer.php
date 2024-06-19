<?php

namespace App\Livewire\Posting;

use App\Enums\Status;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostsContainer extends Component
{
    public $userID;

    protected $listeners = [
        'post-create-delete' => '$refresh'
    ];

    public function mount($userID = null){
        $this->userID = $userID != null ? $userID : null;
    }

    public function render()
    {
        return view('livewire.Posting.posts-container', [
            'posts' => Post::where('status', Status::Available)
                            ->when($this->userID, function ($query, $userID) {
                                return $query->where('user_id', $userID);
                            })
                            ->orderBy('created_at', 'desc')
                            ->get()
        ]);
    }
}
