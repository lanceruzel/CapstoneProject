<?php

namespace App\Livewire\StoreRegistration;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Mail\StoreRegistrationUpdated;
use App\Models\StoreInformation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ViewStoreRegistrationModal extends Component
{
    use WireUiActions;

    public $registration;

    public $contact;
    public $email;
    public $country;
    public $state;

    public $remarks;

    public $requirements;
    public $paypalAccountName;
    public $paypalEmail;
    public $oldRemarks;

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
            $this->state = $this->registration->state;
            $this->paypalAccountName = $this->registration->paypal_merchant_id;
            $this->paypalEmail = $this->registration->paypal_email;
            $this->requirements = json_decode($this->registration->requirements);
            $this->oldRemarks = $this->requirements->remarks;
        }
    }

    public function updateRegistration(){
        if(!$this->updateRegistrationStatus()){
            return;
        }

        if($this->remarks != null || trim($this->remarks) != '' || !empty($this->remarks)){
            $this->requirements->remarks = $this->remarks;
            $this->requirements->status = Status::ForReSubmission;
            $this->update();
        }else{
            $this->dialog()->confirm([
                'title' => 'Are you Sure?',
                'description' => 'Approve this store\'s registration?',
                'acceptLabel' => 'Yes, approve it',
                'method' => 'update',
                'params' => '',
            ]);
        }
    }

    public function update(){
        $this->registration->requirements = json_encode($this->requirements);

        if($this->registration->save()){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Store registration has been successfully updated.',
            ]);

            $this->dispatch('refreshStoreRegistrationTable');

            UserNotif::sendNotif($this->registration->user_id, 'Your store registration has been updated.' , NotificationType::StoreRegistration);
            Mail::to($this->registration->user->email)->send(new StoreRegistrationUpdated($this->registration->user->name()));
            
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

        foreach(array_slice((array) $this->requirements, 0, -2) as $key => $requirement) {
            if ($this->requirements->{$key}->status == Status::Declined) {
                $isAccepted = false;
            }

            if ($this->requirements->{$key}->status == Status::ForReview) { 
                return $this->dialog()->show([
                    'icon' => 'info',
                    'title' => 'Information!',
                    'description' => 'Woops, there are still document/s that are need for review.',
                ]);
            }
        }

        $this->requirements->status = $isAccepted ? Status::Accepted : Status::ForReSubmission;
        return true;
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
            'state',
            'requirements',
            'registration',
            'remarks'
        ]);
    }

    public function render()
    {
        return view('livewire.StoreRegistration.view-store-registration-modal');
    }
}
