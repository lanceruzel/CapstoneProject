<?php

namespace App\Livewire\StoreRegistration;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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
    public $state;

    public $savedRequirements;

    public $paypalMerchantId;
    public $paypalEmail;

    public $dti_permit;
    public $mayors_permit;
    public $business_permit;
    public $bir_registration;
    public $valid_id;
    public $health_and_safety_permit;
    public $business_license;
    public $tax_compliance;

    public $url = "https://api.countrystatecity.in/v1/countries";
    public $countryData = [];
    public $countryOptions;
    public $stateOptions;
    public $validIdType;

    protected $listeners = [
        'clearstoreRegistrationData' => 'clearData',
        'updatedCountry'
    ];

    public function mount(){
        $this->user = auth()->user();

        if($this->user->role == UserType::Travelpreneur || $this->user->role == UserType::Store){
            $this->contact = $this->user->storeInformation->contact;
            $this->email = $this->user->storeInformation->email;
            $this->country = $this->user->storeInformation->country;
            $this->state = $this->user->storeInformation->state;

            $this->savedRequirements = json_decode($this->user->storeInformation->requirements);

            $this->loadCountries();
            $this->loadStates();
        }
    }

    public function loadCountries()
    {
        $response = Http::withHeaders([
            'X-CSCAPI-KEY' => env('COUNTRY_STATE_CITY_API_KEY')
        ])->get($this->url);

        if ($response->successful()) {
            $this->countryData = $response->json();

            $this->countryOptions = collect($this->countryData)->map(function ($country) {
                return [
                    'name' => $country['name'],
                    'value' => $country['name']
                ];
            })
            ->sortBy('name')
            ->values()
            ->toArray();
        } else {
            Log::error('Failed to load countries', ['response' => $response->body()]);
        }
    }

    public function updatedCountry(){
        $this->state = null;
        $this->stateOptions = [];
        $this->loadStates();
    }

    public function loadStates(){
        if(!$this->country){
            return;
        }

        $selectedCountry = collect($this->countryData)->firstWhere('name', $this->country);

        if(!$selectedCountry){
            Log::error('Selected country not found', ['country' => $this->country]);
            return;
        }

        $response = Http::withHeaders([
            'X-CSCAPI-KEY' => env('COUNTRY_STATE_CITY_API_KEY')
        ])->get($this->url . '/' . $selectedCountry['iso2'] . '/states');

        if ($response->successful()) {
            $this->stateOptions = collect($response->json())->map(function ($state) {
                return [
                    'name' => $state['name'],
                    'value' => $state['name']
                ];
            })
            ->sortBy('name')
            ->values()
            ->toArray();
        } else {
            Log::error('Failed to load states', ['response' => $response->body()]);
        }
    }

    public function store(){
        $validated = $this->formValidate();
        
        $storeInformation = $this->user->storeInformation;

        if($this->savedRequirements->status == Status::ForSubmission){
            $storeInformation->contact = $validated['contact'];
            $storeInformation->country = $validated['country'];
            $storeInformation->state = $validated['state'];
            $storeInformation->paypal_merchant_id = $validated['paypalMerchantId'];
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
        foreach (array_slice((array) $this->savedRequirements, 0, -2) as $key => $requirement) {
            if($this->savedRequirements->$key->status != Status::Accepted){
                if($key == 'valid_id'){
                    $this->savedRequirements->$key->type = $validated['validIdType'];
                }

                $this->savedRequirements->$key->file_path = $this->storeDocument($id, $validated[$key]);
                $this->savedRequirements->$key->status = Status::ForReview;
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
                'state' => 'required|min:5',
                'paypalMerchantId' => 'required|min:5',
                'paypalEmail' => 'required|email|min:5',
            ];

            foreach (array_slice((array) $this->savedRequirements, 0, -2) as $key => $requirement) {
                if($requirement->status == Status::Declined || $requirement->status == '' || $requirement->status == null) {
                    if($key == 'valid_id'){
                        $rules['validIdType'] = 'required';
                    }
                    
                    $rules[$key] = 'required|mimes:pdf,png,jpg,jpeg,doc,docx';
                }
            }
            
            // Add email validation if it is different from the stored email
            if ($this->email != $this->user->storeInformation->email) {
                $rules['email'] = 'required|email|unique:store_information';
            }

            $validate = $this->validate($rules);
        }elseif($this->savedRequirements->status == Status::ForReSubmission){
            $rules = [];

            foreach (array_slice((array) $this->savedRequirements, 0, -2) as $key => $requirement) {
                if($requirement->status == Status::Declined) {
                    if($key == 'valid_id'){
                        $rules['validIdType'] = 'required';
                    }

                    $rules[$key] = 'required|mimes:pdf,png,jpg,jpeg,doc,docx';
                }
            }

            // Validate only if there are rules to validate
            if (!empty($rules)) {
                $validate = $this->validate($rules);
            }
        }
 
        return $validate;
    }

    public function storeDocument($id, $document){
        $filename = $id . '_' . time() . '_' . uniqid() . '.' . $document->getClientOriginalExtension();
        $document->storeAs('documents', $filename);

        return $filename;
    }
        
    public function render()
    {
        return view('livewire.StoreRegistration.store-register-form-modal', [
            'registrationStatus' => $this->savedRequirements->status
        ]);
    }
}
