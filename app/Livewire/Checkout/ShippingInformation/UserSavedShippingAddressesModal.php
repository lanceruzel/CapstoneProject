<?php

namespace App\Livewire\Checkout\ShippingInformation;

use App\Models\UserShippingInformation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserSavedShippingAddressesModal extends Component
{
    public $selectedAddress;

    protected $listeners = [
        'refresh-saved-addresses' => '$refresh'
    ];

    public function updateSelectedAddress(){
        $this->dispatch('selected-shipping-address' , ['id' => $this->selectedAddress]);
        $this->dispatch('close-modal', ['modal' => 'viewShippingAddressesModal']);
    }

    public function render()
    {
        return view('livewire.Checkout.ShippingInformation.user-saved-shipping-addresses-modal', [
            'addresses' => UserShippingInformation::where('user_id', Auth::id())->get()
        ]);
    }
}
