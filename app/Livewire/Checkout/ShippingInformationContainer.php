<?php

namespace App\Livewire\Checkout;

use App\Models\UserShippingInformation;
use Livewire\Component;

class ShippingInformationContainer extends Component
{
    public $address = null;

    protected $listeners = [
        'selected-shipping-address' => 'getData'
    ];

    public function getData($id){
        $this->address = UserShippingInformation::findOrFail($id);
    }

    public function render()
    {
        return view('livewire.Checkout.shipping-information-container');
    }
}
