<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPasswordForm extends Component
{
    public $email;

    public function sendEmail(){
        $validated = $this->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink($validated);

        $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
    }

    public function render()
    {
        return view('livewire.Auth.forgot-password-form');
    }
}
