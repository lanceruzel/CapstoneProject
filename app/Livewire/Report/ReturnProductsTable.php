<?php

namespace App\Livewire\Report;

use App\Models\Report;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ReturnProductsTable extends Component
{
    public $filterStatus = [];
    public $search = '';

    protected $listeners = [
        'refresh-return-product-table' => '$refresh'
    ];

    public function getRequests(){
        $filter = $this->filterStatus;

        return Report::where('seller_id', Auth::id())->orderBy('id', 'desc')->paginate(10);
    }

    public function render()
    {
        return view('livewire.Report.return-products-table',[
            'requests' => $this->getRequests()
        ]);
    }
}
