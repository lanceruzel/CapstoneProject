<?php

namespace App\Livewire\StoreRegistration;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use WireUi\Traits\WireUiActions;

class StoreRegisterFormModal extends Component
{
    use WithFileUploads;
    use WireUiActions;

    public $user;

    public $contact;
    public $email;
    public $country;
    public $address;
    public $requirement_1;
    public $requirement_2;
    public $requirement_3;

    public $savedRequirements;

    public $paypalAccountName;
    public $paypalEmail;

    public $validId;
    public $businessPermit;
    public $registrationDTI;
    public $registrationBIR;

    protected $listeners = [
        'clearstoreRegistrationData' => 'clearData'
    ];

    public function mount(){
        $this->user = auth()->user();

        if($this->user->role == UserType::Travelpreneur || $this->user->role == UserType::Store){
            $this->contact = $this->user->storeInformation->contact;
            $this->email = $this->user->storeInformation->email;
            $this->country = $this->user->storeInformation->country;
            $this->address = $this->user->storeInformation->address;

            $this->savedRequirements = json_decode($this->user->storeInformation->requirements);
        }
    }

    public function store(){
        $validated = $this->formValidate();

        $storeInformation = $this->user->storeInformation;

        if($this->savedRequirements->status == Status::ForSubmission){
            $storeInformation->contact = $validated['contact'];
            $storeInformation->country = $validated['country'];
            $storeInformation->address = $validated['address'];
            $storeInformation->paypal_name = $validated['paypalAccountName'];
            $storeInformation->paypal_email = $validated['paypalEmail'];

            if($this->email != $this->user->storeInformation->email){
                $storeInformation->email = $validated['email'];
            }
        }

        $storeInformation->requirements = $this->updateRequirements($storeInformation->id, $validated);

        if($this->user->storeInformation->save()){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Your registration has been successfully submitted.',
            ]);
        }
    }

    public function updateRequirements($id, $validated){
        if($this->savedRequirements->status == Status::ForSubmission){

            if(auth()->user()->role == UserType::Store){
                $this->savedRequirements->businessPermit->file_path = $this->storeDocument($id, $validated['businessPermit']);
                $this->savedRequirements->businessPermit->status = Status::ForReview;

                $this->savedRequirements->registrationDTI->file_path = $this->storeDocument($id, $validated['registrationDTI']);
                $this->savedRequirements->registrationDTI->status = Status::ForReview;

                $this->savedRequirements->registrationBIR->file_path = $this->storeDocument($id, $validated['registrationBIR']);
                $this->savedRequirements->registrationBIR->status = Status::ForReview;
            }

            if(auth()->user()->role == UserType::Travelpreneur){
                $this->savedRequirements->validId->file_path = $this->storeDocument($id, $validated['validId']);
                $this->savedRequirements->validId->status = Status::ForReview;

                $this->savedRequirements->registrationDTI->file_path = $this->storeDocument($id, $validated['registrationDTI']);
                $this->savedRequirements->registrationDTI->status = Status::ForReview;
            }

        }elseif($this->savedRequirements->status == Status::ForReSubmission){

            if(auth()->user()->role == UserType::Store){
                if($this->savedRequirements->businessPermit->status == Status::Declined){
                    $this->savedRequirements->businessPermit->file_path = $this->storeDocument($id, $validated['businessPermit']);
                    $this->savedRequirements->businessPermit->status = Status::ForReview;
                }

                if($this->savedRequirements->registrationDTI->status == Status::Declined){
                    $this->savedRequirements->registrationDTI->file_path = $this->storeDocument($id, $validated['registrationDTI']);
                    $this->savedRequirements->registrationDTI->status = Status::ForReview;
                }

                if($this->savedRequirements->registrationBIR->status == Status::Declined){
                    $this->savedRequirements->registrationBIR->file_path = $this->storeDocument($id, $validated['registrationBIR']);
                    $this->savedRequirements->registrationBIR->status = Status::ForReview;
                }
            }

            if(auth()->user()->role == UserType::Travelpreneur){
                if($this->savedRequirements->registrationDTI->status == Status::Declined){
                    $this->savedRequirements->registrationDTI->file_path = $this->storeDocument($id, $validated['registrationDTI']);
                    $this->savedRequirements->registrationDTI->status = Status::ForReview;
                }

                if($this->savedRequirements->validId->status == Status::Declined){
                    $this->savedRequirements->validId->file_path = $this->storeDocument($id, $validated['validId']);
                    $this->savedRequirements->validId->status = Status::ForReview;
                }
            }
        }

        //Update Overall Status
        $this->savedRequirements->status = Status::ForReview;

        return json_encode($this->savedRequirements);
    }

    public function formValidate(){
        $validate = null;

        if($this->savedRequirements->status == Status::ForSubmission){
            $rules = [
                'country' => 'required',
                'contact' => 'required|min:5',
                'address' => 'required|min:5',
                'paypalAccountName' => 'required|min:5',
                'paypalEmail' => 'required|email|min:5',
            ];

            if(auth()->user()->role == UserType::Store){
                $rules['businessPermit'] = 'required|mimes:pdf';
                $rules['registrationDTI'] = 'required|mimes:pdf';
                $rules['registrationBIR'] = 'required|mimes:pdf';
            }

            if(auth()->user()->role == UserType::Travelpreneur){
                $rules['validId'] = 'required|mimes:pdf';
                $rules['registrationDTI'] = 'required|mimes:pdf';
            }

            // Add email validation if it is different from the stored email
            if ($this->email != $this->user->storeInformation->email) {
                $rules['email'] = 'required|email|unique:store_information';
            }
            
            $validate = $this->validate($rules);

        }elseif($this->savedRequirements->status == Status::ForReSubmission){
            $rules = [];

            foreach (array_slice((array) $this->savedRequirements, 0, -2) as $key => $requirement) {
                if ($requirement->status == Status::Declined) {
                    $rules[$key] = 'required|mimes:pdf';
                }
            }

            // Validate only if there are rules to validate
            if (!empty($rules)) {
                $validate = $this->validate($rules);
            }
        }
        
        return $validate;
    }

    public function getCountries(){
        $countriesJsonPath = public_path('json/countries.json');
        $countries = json_decode(file_get_contents($countriesJsonPath), true);

        // Sort product categories
        return collect($countries)->pluck('name.common')->sort()->values()->toArray();
    }

    public function storeDocument($id, $document){
        $filename = $id . '_' . time() . '_' . uniqid() . '.' . $document->getClientOriginalExtension();
        $document->storeAs('documents', $filename);

        return $filename;
    }
        
    public function render()
    {
        return view('livewire.StoreRegistration.store-register-form-modal', [
            'countries' => $this->getCountries(),
            'registrationStatus' => $this->savedRequirements->status
        ]);
    }
}
