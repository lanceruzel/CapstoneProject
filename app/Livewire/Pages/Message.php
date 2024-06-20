<?php

namespace App\Livewire\Pages;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Message extends Component
{
    public function render()
    {
        return view('livewire.Pages.message');
    }
}
