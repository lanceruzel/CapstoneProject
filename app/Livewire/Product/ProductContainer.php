<?php

namespace App\Livewire\Product;

use Livewire\Component;

class ProductContainer extends Component
{
    public $product;

    public function mount($product){
        $this->product = $product;
    }

    public function render()
    {
        return view('livewire.Product.product-container');
    }
}
