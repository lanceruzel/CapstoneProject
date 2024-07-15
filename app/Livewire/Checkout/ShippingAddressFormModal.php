<?php

namespace App\Livewire\Checkout;

use App\Models\UserShippingInformation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ShippingAddressFormModal extends Component
{
    use WireUiActions;

    public $fullName;
    public $address1;
    public $address2;
    public $postal;
    public $phoneNumber;

    public $addressUpdate = null;

    protected $listeners = [
        'clearshippingAddressFormModalData' => 'clearData',
        'view-address' => 'getData'
    ];

    public function getData($id){
        $this->addressUpdate = UserShippingInformation::findOrFail($id);

        if($this->addressUpdate){
            $this->fullName = $this->addressUpdate->full_name;
            $this->address1 = $this->addressUpdate->address_1;
            $this->address2 = $this->addressUpdate->address_2;
            $this->postal = $this->addressUpdate->postal;
            $this->phoneNumber = $this->addressUpdate->phone_number;
        }
    }       

    public function deleteConfirmation(){
        $this->notification()->confirm([
            'title' => 'Are you sure you want to delete this?',
            'description' => 'Delete Address?',
            'icon' => 'question',
            'accept' => [
            'label' => 'Yes, delete it',
                'method' => 'deleteAddress',
            ],
            'reject' => [
                'label' => 'No, cancel',
            ],
        ]);
    }

    public function deleteAddress(){
        if($this->addressUpdate->delete()){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Your address has been successfully deleted.',
            ]);

            $this->dispatch('close-modal', ['modal' => 'shippingAddressForm']);
            $this->dispatch('refresh-saved-addresses');
        }
    }

    public function store(){
        $validated = $this->formValidate();
        
        try{
            $store = $this->storeAddress($validated);

            if($store){
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => $this->phoneNumber != null ? 'Your address has been successfully updated.' : 'Your address has been successfully added.',
                ]);

                $this->dispatch('close-modal', ['modal' => 'shippingAddressForm']);
                $this->dispatch('refresh-saved-addresses');
            }else{
                $this->notification()->send([
                    'icon' => 'error',
                    'title' => 'Error!',
                    'description' => 'Woops, there\'s an error creating your address.',
                ]);
            }
        } catch (\Exception $e) {
            // Log::error('Error store address: ' . $e->getMessage());

            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, there\'s an error creating your address.',
            ]);
        }
    }

    public function formValidate(){
        return $this->validate([
            'fullName' => 'required|min:5',
            'address1' => 'required|min:10',
            'address2' => 'required|min:10',
            'postal' => 'required|numeric|min:4',
            'phoneNumber' => 'required|min:5'
        ]);
    }

    public function clearData(){
        $this->reset([
            'fullName',
            'address1',
            'address2',
            'postal',
            'phoneNumber',
        ]);

        $this->addressUpdate = null;
    }

    public function storeAddress($validated){
        return UserShippingInformation::updateOrCreate(
            [
                'id' => $this->addressUpdate ? $this->addressUpdate->id : null,
                'user_id' => Auth::id()
            ],
            [
                'full_name' => $validated['fullName'],
                'address_1' => $validated['address1'],
                'address_2' => $validated['address2'],
                'postal' => $validated['postal'],
                'phone_number' => $validated['phoneNumber'],
            ]
        );
    }

    public function render()
    {
        return view('livewire.Checkout.shipping-address-form-modal');
    }
}
