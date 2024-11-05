<?php

namespace App\Livewire\UserManagement;

use App\Models\User;
use Livewire\Component;

class UserDetailsModal extends Component
{
    public $user;
    public $totalDelivered;

    protected $listeners = [
        'clearUserDetailsModal' => 'clearData',
        'viewUserDetails' => 'getData'
    ];

    public function getData($id){
        $this->user = User::findOrFail($id);
    }

    public function clearData(){
        $this->reset([
            'user',
        ]);
    }

    public function render(){
        return view('livewire.UserManagement.user-details-modal');
    }
}
