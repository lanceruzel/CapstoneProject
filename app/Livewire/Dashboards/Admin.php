<?php

namespace App\Livewire\Dashboards;

use App\Models\Product;
use App\Models\User;
use Livewire\Component;

class Admin extends Component
{
    public function getTotalUsers(){
        return count(User::get());
    }

    public function getTotalProducts(){
        return count(Product::get());
    }

    public function render()
    {
        return view('livewire.Dashboards.admin',[
            'totalUsers' => $this->getTotalUsers(),
            'totalProducts' => $this->getTotalProducts()
        ]);
    }
}
