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
        $isRegistered = true;

        if($this->registrations->requirement_1->status != Status::Accepted){
            $isRegistered = false;
        }

        if($this->registrations->requirement_2->status != Status::Accepted){
            $isRegistered = false;
        }

        if($this->registrations->requirement_3->status != Status::Accepted){
            $isRegistered = false;
        }

        return $isRegistered;
    }
}
