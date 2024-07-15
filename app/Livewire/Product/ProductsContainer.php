<?php

namespace App\Livewire\Product;

use App\Enums\Status;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductsContainer extends Component
{
    public $userID;
    public $category;

    protected $listeners = [
        'change-selected-product-category' => 'getSelectedCategory'
    ];

    public function mount($userID = null){
        $this->userID = $userID;
    }

    public function getSelectedCategory($category){
        $this->category = $category;
    }

    public function getProducts(){
        $products = null;

        if($this->userID){
            if($this->userID){
                $products = Product::where('seller_id', $this->userID)->get();
            }else{
                $products = Product::where('status', Status::Available)->get();
            }
        }else{
            if($this->category == 'All' || $this->category == null){
                $products = Product::where('status', Status::Available)->get();
            }else{
                $products = Product::where('status', Status::Available)->where('category', $this->category)->get();
            }
        }

        return $products;
    }
        
    public function render(){
        return view('livewire.Product.products-container',[
            'products' => $this->getProducts()
        ]);
    }
}
