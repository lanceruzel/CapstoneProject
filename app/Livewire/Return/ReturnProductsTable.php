<?php

namespace App\Livewire\Return;

use App\Models\ReturnRequest;
use Livewire\Component;
use Livewire\WithPagination;

class ReturnProductsTable extends Component
{
    use WithPagination;

    protected $listeners = [
        'refresh-return-product-table' => '$refresh'
    ];

    public function getRequests(){
        return ReturnRequest::orderBy('id', 'desc')->paginate(10);
    }

    public function render()
    {
        return view('livewire.Return.return-products-table', [
            'requests' => $this->getRequests()
        ]);
    }
}
