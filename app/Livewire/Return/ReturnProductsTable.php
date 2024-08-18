<?php

namespace App\Livewire\Return;

use App\Models\ReturnRequest;
use Livewire\Component;
use Livewire\WithPagination;

class ReturnProductsTable extends Component
{
    use WithPagination;

    public $search = '';

    public $filterStatus = [];

    protected $listeners = [
        'refresh-return-product-table' => '$refresh'
    ];

    public function getRequests(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return ReturnRequest::whereHas('reporter', function($query){
                $query->whereHas('userinformation', function($query){
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->orWhere('products', 'like', '%' . $this->search . '%')->orderBy('id', 'desc')->paginate(10);
        }else{
            return ReturnRequest::query()
            ->Where(function ($query) use($filter) {
                for ($i = 0; $i < count($filter); $i++){
                    $query->orwhere('status', 'like',  '%' . $filter[$i] .'%');
                }  
            })
            ->whereHas('reporter', function($query){
                $query->whereHas('userinformation', function($query){
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->orWhere('products', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')->paginate(10);
        }
    }

    public function render()
    {
        return view('livewire.Return.return-products-table', [
            'requests' => $this->getRequests()
        ]);
    }
}
