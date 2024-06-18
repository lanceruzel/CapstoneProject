<?php

namespace App\Livewire\Tabs;

use Livewire\Component;

class Home extends Component
{
    public $simpleModal;

    public function render()
    {
        return view('livewire.Tabs.home');
    }
}
