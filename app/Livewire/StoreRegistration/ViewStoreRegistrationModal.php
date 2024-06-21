<?php

namespace App\Livewire\StoreRegistration;

use App\Models\StoreInformation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ViewStoreRegistrationModal extends Component
{
    public $registration;

    public $contact;
    public $email;
    public $country;
    public $address;

    public $requirements;

    protected $listeners = [
        'clearStoreRegistrationData' => 'clearData',
        'viewRegistration' => 'getData'
    ];

    public function getData($id){
        $this->registration = StoreInformation::findOrFail($id);

        if($this->registration){
            $this->contact = $this->registration->contact;
            $this->email = $this->registration->email;
            $this->country = $this->registration->country;
            $this->address = $this->registration->address;
            $this->requirements = json_decode($this->registration->requirements);
        }
    }

    public function clearData(){
        $this->reset([
            'contact', 
            'email',
            'country',
            'address',
            'requirements',
            'registration'
        ]);
    }

    public function render()
    {
        return view('livewire.StoreRegistration.view-store-registration-modal');
    }
}
