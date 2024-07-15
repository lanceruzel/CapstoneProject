<?php

namespace App\Livewire\Order;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersTable extends Component
{
    use WithPagination;

    public $filterStatus = [];

    protected $listeners = [
        'refresh-order-table' => '$refresh'
    ];

    public function getOrders(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return Order::orderBy('id', 'desc')->where('seller_id', Auth::id())->paginate(10);
        }else{
            return Order::query()
            ->Where(function ($query) use($filter) {
                for ($i = 0; $i < count($filter); $i++){
                    $query->orwhere('status', 'like',  '%' . $filter[$i] .'%');
                }  
            })
            ->where('seller_id', Auth::id())
            ->orderBy('id', 'desc')
            ->paginate(10);
        }
    }

    public function render()
    {
        return view('livewire.Order.orders-table', [
            'orders' => $this->getOrders()
        ]);
    }
}
