<?php

namespace App\Livewire\StoreRegistration;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\StoreInformation;
use Livewire\Component;

class StoreRegistrationsStats extends Component
{
    public function render(){
        $totalRegistrations = StoreInformation::count();
        
        $registrations = StoreInformation::select('requirements')->get();
        
        $registrationCounts = [
            Status::Accepted => 0,
            Status::ForReview => 0,
            Status::ForSubmission => 0,
            Status::ForReSubmission => 0,
        ];
        
        foreach ($registrations as $registration) {
            $requirements = json_decode($registration->requirements, true);
            $status = $requirements['status'] ?? null;
            
            if ($status && isset($registrationCounts[$status])) {
                $registrationCounts[$status]++;
            }
        }

        return view('livewire.StoreRegistration.store-registrations-stats', [
            'totalRegistrations' => $totalRegistrations,
            'totalAccepted' => $registrationCounts[Status::Accepted],
            'totalForReview' => $registrationCounts[Status::ForReview],
            'totalForSubmission' => $registrationCounts[Status::ForSubmission],
            'totalForReSubmission' => $registrationCounts[Status::ForReSubmission],
        ]);
    }
}
