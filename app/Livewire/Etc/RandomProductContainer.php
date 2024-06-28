<?php

namespace App\Livewire\Etc;

use App\Enums\Status;
use App\Models\Product;
use Livewire\Component;

class RandomProductContainer extends Component
{
    public function render()
    {
        return view('livewire.Etc.random-product-container', [
            'products' => Product::where('status', Status::Available)->inRandomOrder()->limit(7)->get()
        ]);
    }
}
