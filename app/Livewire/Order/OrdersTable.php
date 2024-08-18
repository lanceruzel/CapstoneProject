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

    public $search = '';

    protected $listeners = [
        'refresh-order-table' => '$refresh'
    ];

    public function getOrders(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return Order::whereHas('user', function($query){
                $query->whereHas('userinformation', function($query){
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->where('seller_id', Auth::id())
            ->orderBy('id', 'desc')
            ->paginate(10);
        }else{
            return Order::query()
            ->Where(function ($query) use($filter) {
                for ($i = 0; $i < count($filter); $i++){
                    $query->orwhere('status', 'like',  '%' . $filter[$i] .'%');
                }  
            })
            ->where('seller_id', Auth::id())
            ->whereHas('user', function($query){
                $query->whereHas('userinformation', function($query){
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
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
