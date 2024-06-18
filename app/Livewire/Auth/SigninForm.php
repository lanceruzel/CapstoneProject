<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class SigninForm extends Component
{
    public $email;
    public $password;

    public function signin(){
        $validated = $this->formValidate();

        try{
            //Attempt login
            if(Auth::attempt($validated)){
                session()->regenerate();
    
                return redirect()->route('home');
            }else{
                session()->flash('fail', 'These credentials do not match our records.');
            }

        }catch (\Exception $e){
            //Log the error for debugging
            Log::error('Error signin: ' . $e->getMessage());
    
            //Show error dialog
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, there seems to be a problem logging in your account. Please try again later.',
            ]);
        }

    }

    public function formValidate(){
        return $this->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
    }

    public function render()
    {
        return view('livewire.Auth.signin-form');
    }
}
