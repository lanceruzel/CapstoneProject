<?php

namespace App\Livewire\StoreRegistration;

use App\Models\StoreInformation;
use Livewire\Component;

class StoreRegistrationsTable extends Component
{
    public function render()
    {
        return view('livewire.StoreRegistration.store-registrations-table',[
            'registrations' => StoreInformation::where('requirements', 'like', '%for-review%')->orderBy('created_at', 'desc')->get()
        ]);
    }
}
