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

        foreach(array_slice((array) $this->registrations, 0, -2) as $key => $registration) {
            if($registration->status != Status::Accepted){
                $isRegistered = false;
                break;
            }
        }

        return $isRegistered;
    }
}
