<?php

namespace App\Livewire\Auth;

use App\Enums\Status;
use App\Models\User;
use App\Models\UserInformation;
use App\Enums\UserType;
use App\Models\StoreInformation;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Ramsey\Collection\Collection;
use WireUi\Traits\WireUiActions;

class SignupForm extends Component
{
    use WireUiActions;

    public $firstName;
    public $lastName;
    public $birthdate;
    public $gender;
    public $country = '';
    public $state = '';
    public $username;
    public $email;
    public $password;
    public $password_confirmation;

    public $countryOptions;
    public $stateOptions;
    public $userType = UserType::ContentCreator;

    public $url = "https://api.countrystatecity.in/v1/countries";
    public $countryData = [];

    protected $listeners = [
        'updatedCountry'
    ];

    public function mount($type = null){
        if($type != null){
            if($type == UserType::Travelpreneur){
                $this->userType = $type;
            }else{
                abort(404, 'Invalid Url');
            }
        }

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
    
            if($account) {
                $userInformation = $this->insertUserInformation($account, $validated);

                if($userInformation) {
                    if($this->userType == UserType::Travelpreneur){
                        $storeInformation = $this->insertStoreInformation($account, $validated);

                        if($storeInformation){
                            // return redirect()->route('login')->with('success', 'Your account has been successfully created.'); 
                        }else{
                            // Delete the user row
                            User::destroy($account->id);

                            //Delete the userinformation row
                            UserInformation::where('user_id', $account->id)->delete();
                        }       

                    }else{
                        // return redirect()->route('login')->with('success', 'Your account has been successfully created.');
                    }

                    Auth::login($account);
                    event(new Registered($account));
                    
                    return redirect()->route('verification.notice');
                }else{
                    // Delete the user row
                    User::destroy($account->id);
                }
            }

            //Show error dialog
            $this->dialog()->show([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, there seems to be a problem creating your account. Please try again later.',
            ]);
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

    public function formValidate(){
        return $this->validate([
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'birthdate' => 'required|date|before:-18 years',
            'gender' => 'required',
            'country' => 'required',
            'state' => 'required',
            'username' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'password' => [
                'required', 
                'confirmed', 
                Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(), //disabled for the meantime for development stage
            ],
            'password_confirmation' => 'required'
        ], [
            'birthdate.before' => 'You must be at least 18 years old.',
        ]);
    }

    public function createAccount($validated){
        return User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $this->userType
        ]);
    }

    public function insertStoreInformation($account, $validated){
        return StoreInformation::create([
            'user_id' => $account->id,
            'name' => $validated['firstName'] . ' ' . $validated['lastName'] . '\'s Store',
            'email' => $validated['email'],
            'country' => $validated['country'],
            'state' => $validated['state'],
            'requirements' => $this->storeRequirementsFormat()
        ]);
    }

    public function storeRequirementsFormat(){
        $format = [
            'validId' => [
                'type' => '',
                'file_path' => '',
                'status' => '',
            ],
            'registrationDTI' => [
                'file_path' => '',
                'status' => '',
            ],
            'status' => Status::ForSubmission,
            'remarks' => ''
        ];

        return json_encode($format);
    }

    public function insertUserInformation($account, $validated){
        return UserInformation::create([
            'user_id' => $account->id,
            'first_name' => $validated['firstName'],
            'last_name' => $validated['lastName'],
            'gender' => $validated['gender'],
            'birthdate' => $validated['birthdate'], 
            'country' => $validated['country'],
            'state' => $validated['state']
        ]);
    }

    public function render()
    {
        return view('livewire.Auth.signup-form');
    }
}
