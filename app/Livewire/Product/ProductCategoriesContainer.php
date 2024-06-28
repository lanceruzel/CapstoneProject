<?php

namespace App\Livewire\Product;

use Livewire\Component;

class ProductCategoriesContainer extends Component
{
    public $categories = [];

    public $category = 'All';
    
    protected $listeners = [
        'change-selected-product-category' => 'getSelectedCategory'
    ];

    public function getSelectedCategory($category){
        $this->category = $category;
    }

    public function getCategories(){
        $productCategoriesJsonPath = public_path('json/product_categories.json');
        $productCategories = json_decode(file_get_contents($productCategoriesJsonPath), true);

        $this->categories = collect($productCategories['categories'])->sortBy('name')->values()->toArray();
    }

    public function render(){
        $this->getCategories();

        return view('livewire.Product.product-categories-container', [
            'selectedCategory' => $this->category
        ]);
    }
}
