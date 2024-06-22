<?php

namespace App\Livewire\StoreRegistration;

use App\Enums\Status;
use App\Models\StoreInformation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ViewStoreRegistrationModal extends Component
{
    use WireUiActions;

    public $registration;

    public $contact;
    public $email;
    public $country;
    public $address;

    public $remarks;

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

    public function updateRegistration(){
        $this->updateRegistrationStatus();
        $this->requirements->remarks = $this->remarks;
        $this->registration->requirements = json_encode($this->requirements);

        if($this->registration->save()){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Store registration has been successfully updated.',
            ]);

            $this->dispatch('refreshStoreRegistrationTable');
        }else{
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, there seems to be a problem updating this store registration.',
            ]);
        }

        //Close Modal
        $this->dispatch('close-modal', ['modal' => 'storeRegistrationModal']);
    }

    public function updateRegistrationStatus(){
        $isAccepted = true;

        if($this->requirements->requirement_1->status == Status::Declined){
            $isAccepted = false;
        }

        if($this->requirements->requirement_2->status == Status::Declined){
            $isAccepted = false;
        }

        if($this->requirements->requirement_3->status == Status::Declined){
            $isAccepted = false;
        }

        if($isAccepted){
            $this->requirements->status = Status::Accepted;

        }else{
            $this->requirements->status = Status::ForReSubmission;
        }

    }

    public function acceptDocument($model){
        $this->requirements->$model->status = Status::Accepted;
    }

    public function declineDocument($model){
        $this->requirements->$model->status = Status::Declined;
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
