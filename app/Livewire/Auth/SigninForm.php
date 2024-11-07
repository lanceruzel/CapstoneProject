<?php

namespace App\Livewire\Auth;

use App\Classes\CurrencyConverter;
use App\Classes\Location;
use App\Enums\UserType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class SigninForm extends Component
{
    use WireUiActions;

    public $email;
    public $password;

    public function signin(){
        $validated = $this->formValidate();

        try{
            //Attempt login
            if(Auth::attempt($validated)){
                session()->regenerate();
    
                if(auth()->user()->role == UserType::Admin){
                    return redirect()->route('admin.store-registrations');
                }elseif(auth()->user()->role != UserType::Store){
                    Auth::user()->userInformation->current_country = Location::getLocation();
                    Auth::user()->userInformation->save();
                }

                CurrencyConverter::loadCurrencyData();

                if(auth()->user()->email_verified_at == null){
                    return redirect()->route('verification.notice');
                }

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
