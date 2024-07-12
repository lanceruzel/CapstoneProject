<?php

namespace App\Livewire\Affiliate;

use Livewire\Component;

class StoreAffiliatesTable extends Component
{
    public $affiliates = [];

    public function render()
    {
        return view('livewire.Affiliate.store-affiliates-table');
    }
}
