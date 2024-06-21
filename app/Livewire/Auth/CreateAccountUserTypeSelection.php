<?php

namespace App\Livewire\Auth;

use App\Enums\UserType;
use Livewire\Component;

class CreateAccountUserTypeSelection extends Component
{
    public $userType = null;

    public function proceed(){
        if($this->userType != null){
            if($this->userType == UserType::Store){
                return redirect()->route('store-signup');
            }else{
                return redirect()->route('signup', $this->userType);
            }
        }
    }

    public function render()
    {
        return view('livewire.Auth.create-account-user-type-selection');
    }
}
