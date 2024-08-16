<?php

namespace App\Livewire\Auth;

use App\Enums\UserType;
use Livewire\Component;

class CreateAccountUserTypeSelection extends Component
{
    public function render()
    {
        return view('livewire.Auth.create-account-user-type-selection');
    }
}
