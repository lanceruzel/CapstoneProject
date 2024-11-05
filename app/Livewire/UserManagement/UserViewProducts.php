<?php

namespace App\Livewire\UserManagement;

use App\Models\Product;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserViewProducts extends Component
{
    use WithPagination;

    public $userId;

    protected $listeners = [
        'clearViewProductsModal' => 'clearData',
        'viewProducts' => 'getData'
    ];

    public $filterStatus = [];

    public $search = '';

    public function getData($id)
    {
        $this->userId = $id;

    }

    public function clearData()
    {
        $this->reset([
            'userId',
        ]);
    }

    public function getProducts(){
        $filter = $this->filterStatus;

        if(empty($filter)){
            return Product::where('name', 'like', '%' . $this->search . '%')->where('seller_id', $this->userId)->orderBy('id', 'desc')->paginate(10);
        }else{
            return Product::query()
            ->Where(function ($query) use($filter) {
                for ($i = 0; $i < count($filter); $i++){
                    $query->orwhere('status', 'like',  '%' . $filter[$i] .'%');
                }  
            })
            ->where('seller_id', $this->userId)
            ->where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        }
    }

    public function render(){
        return view('livewire.UserManagement.user-view-products', [
            'products' => $this->getProducts()
        ]);
    }
}
