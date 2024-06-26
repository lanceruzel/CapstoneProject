<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ProductsTable extends Component
{
    use WithPagination;

    protected $listeners = [
        'refresh-product-table' => '$refresh'
    ];

    public function render()
    {
        return view('livewire.Product.products-table', [
            'products' => Product::where('seller_id', Auth::id())->paginate(10)
        ]);
    }
}
