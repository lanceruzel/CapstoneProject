<?php

namespace App\Livewire\UserManagement;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class UserViewOrders extends Component
{
    use WithPagination;

    public $filterStatus = [];

    public $search = '';
    public $userId;
    public $orders;

    protected $listeners = [
        'clearViewOrdersModal' => 'clearData',
        'viewOrders' => 'getData'
    ];

    public function getData($id)
    {
        $this->userId = $id;
        $this->orders = $this->getOrders();
    }

    public function clearData()
    {
        $this->reset([
            'userId',
            'orders'
        ]);
    }

    public function getOrders(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return Order::whereHas('user', function($query){
                $query->whereHas('userinformation', function($query){
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->where('seller_id', $this->userId)
            ->orderBy('id', 'desc')
            // ->paginate(10);
            ->get();
        }else{
            return Order::query()
            ->Where(function ($query) use($filter) {
                for ($i = 0; $i < count($filter); $i++){
                    $query->orwhere('status', 'like',  '%' . $filter[$i] .'%');
                }  
            })
            ->where('seller_id', $this->userId)
            ->whereHas('user', function($query){
                $query->whereHas('userinformation', function($query){
                    $query->where('first_name', 'like', '%' . $this->search . '%')
                    ->orWhere('last_name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('id', 'desc')
            // ->paginate(10);
            ->get();
        }
    }

    public function render()
    {
        return view('livewire.UserManagement.user-view-orders');
    }
}
