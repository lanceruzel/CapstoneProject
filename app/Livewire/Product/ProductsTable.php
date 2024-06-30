<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsTable extends Component
{
    use WithPagination;

    public $filterStatus = [];

    protected $listeners = [
        'refresh-product-table' => '$refresh'
    ];

    public function getProducts(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return Product::orderBy('created_at', 'desc')->where('seller_id', Auth::id())->paginate(10);
        }else{
            return Product::query()
            ->Where(function ($query) use($filter) {
                for ($i = 0; $i < count($filter); $i++){
                    $query->orwhere('status', 'like',  '%' . $filter[$i] .'%');
                }  
            })
            ->where('seller_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        }
    }

    public function render()
    {
        return view('livewire.Product.products-table', [
            'products' => $this->getProducts()
        ]);
    }
}
