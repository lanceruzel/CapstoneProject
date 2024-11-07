<?php

namespace App\Livewire\Auth;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\StoreInformation;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class StoreSignupForm extends Component
{
    use WireUiActions;

    public $name;
    public $country;
    public $state;
    public $contact;
    public $username;
    public $email;
    public $password;
    public $password_confirmation;
    
    public $url = "https://api.countrystatecity.in/v1/countries";
    public $countryOptions;
    public $stateOptions;
    public $countryData = [];

    protected $listeners = [
        'updatedCountry'
    ];

    public function mount(){
        $this->loadCountries();
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

    public function signup(){
        $validated = $this->formValidate();

        try{
            $account = $this->createAccount($validated);
    
            if ($account) {
                if($this->insertStoreInformation($account, $validated)) {
                    $this->dialog()->show([
                        'icon' => 'info',
                        'title' => 'Info!',
                        'description' => 'Your account has been successfully created. We may require you for further details of your store owner as you register your store in our system.',
                    ]);

                    Auth::login($account);
                    event(new Registered($account));
                    
                    return redirect()->route('verification.notice');
                }else{
                    // Delete the user row
                    User::destroy($account->id);
                }
            }
        }catch (\Exception $e){
            // Delete the user row
            User::destroy($account->id);

            //Log the error for debugging
            Log::error('Error signup: ' . $e->getMessage());
    
            //Show error dialog
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, there seems to be a problem creating your account. Please try again later.',
            ]);
        }
    }

    public function redirectToSignin(){
        return redirect()->route('login')->with('success', 'Your account has been successfully created. You may now signin to your account!');
    }

    public function createAccount($validated){
        return User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => UserType::Store
        ]);
    }

    public function storeRequirementsFormat(){
        $format = [
            'businessPermit' => [
                'file_path' => '',
                'status' => '',
            ],
            'registrationDTI' => [
                'file_path' => '',
                'status' => '',
            ],
            'registrationBIR' => [
                'file_path' => '',
                'status' => '',
            ],
            'status' => Status::ForSubmission,
            'remarks' => ''
        ];

        return json_encode($format);
    }

    public function insertStoreInformation($account, $validated){
        return StoreInformation::create([
            'user_id' => $account->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact' => $validated['contact'],
            'country' => $validated['country'],
            'state' => $validated['state'],
            'requirements' => $this->storeRequirementsFormat()
        ]);
    }

    public function formValidate(){
        return $this->validate([
            'name' => 'required|min:3',
            'country' => 'required',
            'contact' => 'required|min:5',
            'state' => 'required',
            'username' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)->letters()->mixedCase()->numbers()->symbols(), 
            ],
            'password_confirmation' => 'required'
        ]);
    }

    public function render()
    {
        return view('livewire.Auth.store-signup-form');
    }
}
