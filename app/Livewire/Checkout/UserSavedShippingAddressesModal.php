<?php

namespace App\Livewire\Checkout;

use App\Models\UserShippingInformation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserSavedShippingAddressesModal extends Component
{
    public function render()
    {
        return view('livewire.Checkout.user-saved-shipping-addresses-modal', [
            'addresses' => UserShippingInformation::where('user_id', Auth::id())->get()
        ]);
    }
}
