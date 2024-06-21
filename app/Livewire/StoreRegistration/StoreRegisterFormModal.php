<?php

namespace App\Livewire\StoreRegistration;

use App\Enums\UserType;
use App\Models\User;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class StoreRegisterFormModal extends Component
{
    use WithFileUploads;

    public $contact;
    public $email;
    public $country;
    public $address;
    public $requirement_1;
    public $requirement_2;
    public $requirement_3;

    protected $listeners = [
        'clearstoreRegistrationData' => 'clearData'
    ];

    public function mount(){
        $user = auth()->user();

        if($user->role == UserType::Travelpreneur || $user->role == UserType::Store){
            $this->contact = $user->storeInformation->contact;
            $this->email = $user->storeInformation->email;
            $this->country = $user->storeInformation->country;
            $this->address = $user->storeInformation->address;
        }
    }

    public function store(){
        $validated = $this->formValidate();
    }

    public function formValidate(){
        return $this->validate([
            'country' => 'required',
            'contact' => 'required|min:5',
            'address' => 'required|min:5',
            'email' => 'required|email|unique:store_informations',
            'requirement_1' => 'required|mimes:pdf',
            'requirement_2' => 'required|mimes:pdf',
            'requirement_3' => 'required|mimes:pdf',
        ]);
    }

    public function getCountries(){
        $countriesJsonPath = public_path('json/countries.json');
        $countries = json_decode(file_get_contents($countriesJsonPath), true);

        // Sort product categories
        return collect($countries)->pluck('name.common')->sort()->values()->toArray();
    }

    public function clearData(){

    }
        
    public function render()
    {
        return view('livewire.StoreRegistration.store-register-form-modal', [
            'countries' => $this->getCountries()
        ]);
    }
}
