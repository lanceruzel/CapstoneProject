<?php

namespace App\Livewire\StoreRegistration;

use App\Enums\Status;
use App\Models\StoreInformation;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class StoreRegistrationsTable extends Component
{
    public $filterStatus = ['for-review', 'for-resubmission'];

    protected $listeners = [
        'refreshStoreRegistrationTable' => '$refresh'
    ];

    public function getRegistrations(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return StoreInformation::orderBy('created_at', 'desc')->get();
        }else{
            return StoreInformation::query()
            ->Where(function ($query) use($filter) {
                for ($i = 0; $i < count($filter); $i++){
                    $query->orwhere('requirements', 'like',  '%' . $filter[$i] .'%');
                }  
            })
            ->orderBy('created_at', 'desc')
            ->get();
        }
    }

    public function render()
    {
        return view('livewire.StoreRegistration.store-registrations-table',[
            'registrations' => $this->getRegistrations()
        ]);
    }
}
