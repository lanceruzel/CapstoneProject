<?php

namespace App\Classes;

use App\Enums\Status;
use App\Enums\UserType;

class StoreRegistration
{
    private $registrations = null;

    public function __construct()
    {
        $user = auth()->user();

        if ($user->role == UserType::Store || $user->role == UserType::Travelpreneur) {
            $this->registrations = json_decode($user->storeInformation->requirements);
        }
    }

    public function isRegistered(){
        if($this->registrations->status != Status::Accepted){
            return false;
        }

        return true;
    }
}
