<?php

namespace App\Livewire\Auth;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\StoreInformation;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class StoreSignupForm extends Component
{
    use WireUiActions;

    public $name;
    public $country;
    public $address;
    public $contact;
    public $username;
    public $email;
    public $password;
    public $password_confirmation;

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
    
            if ($account) {
                if($this->insertStoreInformation($account, $validated)) {
                    $this->dialog()->show([
                        'icon' => 'info',
                        'title' => 'Info!',
                        'description' => 'Your account has been successfully created. We may require you for further details of your store owner as you register your store in our system.',
                        'onClose' => [
                                'method' => 'redirectToSignin',
                            ],
                            'onDismiss' => [
                                'method' => 'redirectToSignin',
                            ],
                            'onTimeout' => [
                                'method' => 'redirectToSignin',
                            ],
                    ]);
                }else{
                    // Delete the user row
                    User::find($account->id)->destroy();
                }
            }
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

    public function redirectToSignin(){
        return redirect()->route('signin')->with('success', 'Your account has been successfully created. You may now signin to your account!');
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

    public function insertStoreInformation($account, $validated){
        return StoreInformation::create([
            'user_id' => $account->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'contact' => $validated['contact'],
            'country' => $validated['country'],
            'address' => $validated['address'],
            'requirements' => $this->storeRequirementsFormat()
        ]);
    }

    public function formValidate(){
        return $this->validate([
            'name' => 'required|min:3',
            'country' => 'required',
            'contact' => 'required|min:5',
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

    public function render()
    {
        return view('livewire.Auth.store-signup-form', [
            'countries' => $this->getCountries()
        ]);
    }
}
