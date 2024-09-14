<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ChangePasswordModal extends Component
{
    use WireUiActions;
    
    public $currentPassword;
    public $password;
    public $password_confirmation;

    protected $listeners = [
        'clearUpdatePasswordModal' => 'clearData'
    ];

    public function clearData(){
        $this->reset([
            'currentPassword',
            'password',
            'password_confirmation'
        ]);
    }

    public function confirmation(){
        $this->validate([
            'currentPassword' => 'required',
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(),
            ],
        ]);

        $this->dialog()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Change your password?',
            'acceptLabel' => 'Yes, update it',
            'method' => 'updatePassword',
        ]);
    }

    public function updatePassword(){
        if(Hash::check($this->currentPassword, auth()->user()->password)){
            auth()->user()->update([
                'password' => Hash::make($this->password),
            ]);

            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Your password has been successfully updated.',
            ]);

            $this->dispatch('close-modal', ['modal' => 'updatePasswordModal']);
        }else{
            $this->addError('currentPassword', 'The current password you entered does not match our records.');
        }
    }

    public function render()
    {
        return view('livewire.Auth.change-password-modal');
    }
}
