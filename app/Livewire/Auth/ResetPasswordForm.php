<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordValidation;
use Livewire\Component;
use Illuminate\Support\Str;

class ResetPasswordForm extends Component
{
    public $password;
    public $password_confirmation;
    public $email;

    public $token;

    public function mount($token){
        $this->token = $token;
    }

    public function resetPassword(){
        $validated = $this->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => [
                'required', 
                'confirmed', 
                PasswordValidation::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(), //disabled for the meantime for development stage
            ],
            'password_confirmation' => 'required'
        ]);


        $status = Password::reset([
            'email' => $validated['email'],
            'password' => $validated['password'],
            'password_confirmation' => $validated['password_confirmation'],
            'token' => $this->token
        ],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
     
                $user->save();
     
                event(new PasswordReset($user));
            }
        );

        $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
    }

    public function render()
    {
        return view('livewire.Auth.reset-password-form');
    }
}
