<?php

namespace App\Livewire\Tabs;

use App\Enums\Status;
use App\Models\Post;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        return view('livewire.Tabs.home');
    }
}
