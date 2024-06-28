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

    public function getProducts(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return Product::orderBy('created_at', 'desc')->paginate(10);
        }else{
            return Product::query()
            ->Where(function ($query) use($filter) {
                for ($i = 0; $i < count($filter); $i++){
                    $query->orwhere('status', 'like',  '%' . $filter[$i] .'%');
                }  
            })
            ->orderBy('created_at', 'desc')
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
