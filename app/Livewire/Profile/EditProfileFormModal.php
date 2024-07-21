<?php

namespace App\Livewire\Profile;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use WireUi\Traits\WireUiActions;

class EditProfileFormModal extends Component{
    use WithFileUploads;
    use WireUiActions;

    public $email;
    public $firstName;
    public $lastName;
    public $username;

    public $profileDP;
    public $profileBio;

    public $name;

    public $account;

    public function mount(){
        $this->account = User::findOrFail(Auth::id());

        if($this->account){
            if($this->account->role == UserType::Store){
                $this->name = $this->account->storeInformation->name;
                $this->profileBio = $this->account->storeInformation->profile_bio;
                $this->profileDP = $this->account->storeInformation->profile_picture;
            }else{
                $this->firstName = $this->account->userInformation->first_name;
                $this->lastName = $this->account->userInformation->last_name;
                $this->profileBio = $this->account->userInformation->profile_bio;
                $this->profileDP = $this->account->userInformation->profile_picture;
            }

            $this->email = $this->account->email;
            $this->username = $this->account->username;
        }
    }

    protected $listeners = [
        'clearEditProfileFormModalData' => 'clearData',
    ];

    public function update(){
        $validated = $this->formValidate();

        try{
            $this->account->update([
                'username' => $this->username,
                'email' => $this->email,
            ]);
        
             // Update profile picture if changed
            if ($this->profileDP != $this->account->profilePicture()) {
                $filename = time() . '_' . uniqid() . '.' . $this->profileDP->getClientOriginalExtension();
    
                $profileDPPath = $this->profileDP->storeAs('dp', $filename);
    
                if($this->account->role == UserType::Store){
                    $this->account->storeInformation->update(['profile_picture' => $profileDPPath]);
                }else{
                    $this->account->userInformation->update(['profile_picture' => $profileDPPath]);
                }
            }
    
            // Update name and profile bio
            if($this->account->role == UserType::Store){
                $this->account->storeInformation->update([
                    'name' => $this->name,
                    'profile_bio' => $this->profileBio,
                ]);
            }else{
                $this->account->userInformation->update([
                    'first_name' => $this->firstName,
                    'last_name' => $this->lastName,
                    'profile_bio' => $this->profileBio,
                ]);
            }

            $this->dispatch('close-modal', ['modal' => 'editProfileFormModal']);

            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Profile successfully updated.',
            ]);

            return redirect()->to('profile'); 

        }catch(\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error Notification!',
                'description' => 'Woops, its an error. ' . $e->getMessage(),
            ]);
        }
        
    }

    public function formValidate() {
        $rules = [
            'username' => 'required|min:5',
            'profileBio' => 'required|min:10',
        ];
    
        // Check role and existence of relationship
        if ($this->account->role == UserType::Store) {
            $profileDPChanged = $this->profileDP && $this->profileDP !== $this->account->storeInformation->profile_picture;
            $rules['name'] = 'required|min:3';
        } else {
            $profileDPChanged = $this->profileDP && $this->profileDP !== $this->account->userInformation->profile_picture;
            $rules['firstName'] = 'required|min:3';
            $rules['lastName'] = 'required|min:3';
        }
    
        if ($profileDPChanged) {
            $rules['profileDP'] = 'required|image|mimes:png,jpg,jpeg';
        }
    
        if ($this->email != $this->account->email) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->account->id;
        }
    
        return $this->validate($rules);
    }

    public function render()
    {
        return view('livewire.Profile.edit-profile-form-modal');
    }
}
