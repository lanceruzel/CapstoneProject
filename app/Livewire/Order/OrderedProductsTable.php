<?php

namespace App\Livewire\Order;

use App\Enums\Status;
use App\Livewire\Pages\OrderedProduct;
use App\Models\OrderedItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OrderedProductsTable extends Component
{
    use WithPagination;

    public $search = '';

    protected $listeners = [
        'refresh-ordered-table' => '$refresh'
    ];

    public function getOrderedProducts(){
        return OrderedItem::whereHas('order', function($query){
            $query->where('status', Status::OrderBuyerReceived)->where('seller_id', Auth::id());
        })->whereHas('product', function($query){
            $query->where('name', 'like', '%' . $this->search . '%');
        })->paginate(10);
    }

    public function render()
    {
        return view('livewire.Order.ordered-products-table', [
            'ordered' => $this->getOrderedProducts()
        ]);
    }
}
