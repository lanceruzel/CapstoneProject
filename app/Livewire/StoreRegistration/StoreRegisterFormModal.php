<?php

namespace App\Livewire\StoreRegistration;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\User;
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

        $storeInformation->contact = $validated['contact'];
        $storeInformation->country = $validated['country'];
        $storeInformation->address = $validated['address'];
        $storeInformation->requirements = $this->updateRequirements($storeInformation->id, $validated);

        if($this->email != $this->user->storeInformation->email){
            $storeInformation->email = $validated['email'];
        }

        if($this->user->storeInformation->save()){
            // $this->dialog()->show([
            //     'title' => 'Success!',
            //     'icon' => 'success',
            //     'description' => 'Your registration has been successfully submitted.',
            // ]);

            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Your registration has been successfully submitted.',
            ]);
        }
    }

    public function updateRequirements($id, $validated){
        //Save paths
        $this->savedRequirements->requirement_1->file_path = $this->storeDocument($id, $validated['requirement_1']);
        $this->savedRequirements->requirement_2->file_path = $this->storeDocument($id, $validated['requirement_2']);
        $this->savedRequirements->requirement_3->file_path = $this->storeDocument($id, $validated['requirement_3']);

        //Update document status
        $this->savedRequirements->requirement_1->status = Status::ForReview;
        $this->savedRequirements->requirement_2->status = Status::ForReview;
        $this->savedRequirements->requirement_3->status = Status::ForReview;

        //Update Overall Status
        $this->savedRequirements->status = Status::ForReview;

        return json_encode($this->savedRequirements);
    }

    public function formValidate(){
        $validate = null;
        if($this->email == $this->user->storeInformation->email){
            //Exclude email if it the same 
            $validate = $this->validate([
                'country' => 'required',
                'contact' => 'required|min:5',
                'address' => 'required|min:5',
                'requirement_1' => 'required|mimes:pdf',
                'requirement_2' => 'required|mimes:pdf',
                'requirement_3' => 'required|mimes:pdf',
            ]);
        }else{
            $validate = $this->validate([
                'country' => 'required',
                'contact' => 'required|min:5',
                'address' => 'required|min:5',
                'email' => 'required|email|unique:store_information',
                'requirement_1' => 'required|mimes:pdf',
                'requirement_2' => 'required|mimes:pdf',
                'requirement_3' => 'required|mimes:pdf',
            ]);
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
