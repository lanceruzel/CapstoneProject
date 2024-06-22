<?php

namespace App\Livewire\Auth;

use App\Enums\Status;
use App\Models\User;
use App\Models\UserInformation;
use App\Enums\UserType;
use App\Models\StoreInformation;
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
    public $country;
    public $address;
    public $username;
    public $email;
    public $password;
    public $password_confirmation;

    public $userType = UserType::ContentCreator;

    public function mount($type = null){
        if($type != null){
            if($type == UserType::Travelpreneur){
                $this->userType = $type;
            }else{
                abort(404, 'Invalid Url');
            }
        }
    }

    public function getCountries(){
        $countriesJsonPath = public_path('json/countries.json');
        $countries = json_decode(file_get_contents($countriesJsonPath), true);

        // Sort product categories
        return collect($countries)->pluck('name.common')->sort()->values()->toArray();
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
                            return redirect()->route('signin')->with('success', 'Your account has been successfully created.'); 
                        }else{
                            // Delete the user row
                            User::find($account->id)->destroy();

                            //Delete the userinformation row
                            UserInformation::where('user_id', $account->id)->destroy();
                        }       

                    }else{
                        return redirect()->route('signin')->with('success', 'Your account has been successfully created.');
                    }
                }else{
                    // Delete the user row
                    User::find($account->id)->destroy();
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
            User::find($account->id)->destroy();
            
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
            'birthdate' => 'required|date|before:tomorrow',
            'gender' => 'required',
            'country' => 'required',
            'address' => 'required|min:5',
            'username' => 'required|min:5',
            'email' => 'required|email|unique:users',
            'password' => [
                'required', 
                'confirmed', 
                // Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised(), //disabled for the meantime for development stage
            ],
            'password_confirmation' => 'required'
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
            'address' => $validated['address'],
            'requirements' => $this->storeRequirementsFormat()
        ]);
    }

    public function storeRequirementsFormat(){
        $format = [
            'requirement_1' => [
                'file_path' => '',
                'status' => '',
            ],
            'requirement_2' => [
                'file_path' => '',
                'status' => '',
            ],
            'requirement_3' => [
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
            'address' => $validated['address']
        ]);
    }

    public function render()
    {
        return view('livewire.Auth.signup-form', [
            'countries' => $this->getCountries()
        ]);
    }
}
