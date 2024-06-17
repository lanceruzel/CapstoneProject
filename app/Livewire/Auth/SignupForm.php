<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Ramsey\Collection\Collection;

class SignupForm extends Component
{
    public function getCountries(){
        $countriesJsonPath = public_path('json/countries.json');
        $countries = json_decode(file_get_contents($countriesJsonPath), true);

        // Sort product categories
        return collect($countries)->pluck('name.common')->sort()->values()->toArray();
    }

    public function signup(){
        
    }

    public function render()
    {
        return view('livewire.Auth.signup-form', [
            'countries' => $this->getCountries()
        ]);
    }
}
