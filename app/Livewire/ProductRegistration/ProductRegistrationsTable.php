<?php

namespace App\Livewire\ProductRegistration;

use App\Models\Product;
use Livewire\Component;

class ProductRegistrationsTable extends Component
{
    protected $listeners = [
        'refresh-product-registrations-table' => '$refresh'
    ];

    public $filterStatus = ['for-review', 'for-resubmission'];

    public $search = '';

    public function getProducts(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return Product::whereHas('seller', function($query){
                $query->whereHas('userinformation', function($query){
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        }else{
            return Product::query()
            ->Where(function ($query) use($filter) {
                for ($i = 0; $i < count($filter); $i++){
                    $query->orwhere('status', 'like',  '%' . $filter[$i] .'%');
                }  
            })
            ->whereHas('seller', function($query){
                $query->whereHas('userinformation', function($query){
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->orWhere('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        }
    }

    public function render()
    {
        return view('livewire.ProductRegistration.product-registrations-table', [
            'products' => $this->getProducts()
        ]);
    }
}
