<?php

namespace App\Livewire\Product;

use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ProductFormModal extends Component
{
    public $images;
    public $name;
    public $description;
    public $category;

    public $hasVariation = false;

    public $variations = [
        0 => [
            'type' => '',
            'value' => '',
            'stocks' => '',
            'price' => '',
        ]
    ];

    protected $listeners = [
        'clearProductFormModalData' => 'clearData'
    ];

    protected $rules = [
        'name' => 'required|min:10',
        'category' => 'required',
        'description' => 'required|min:50',
        'images.*' => 'required|image|mimes:png,jpg,jpeg',
        'variations.*.type' => 'required',
        'variations.*.value' => 'required',
        'variations.*.stocks' => 'required|numeric',
        'variations.*.price' => 'required|numeric',
    ];

    public function store(){
        $this->validate();
    }

    public function addVariation() {
        $this->variations[] = [
          'type' => '',
          'value' => '',
          'stocks' => '',
          'price' => '',
        ];
    }

    public function removeVariation($variationKey) {
        if (isset($this->variations[$variationKey])) {
          unset($this->variations[$variationKey]);
        }
    }

    public function getCategories(){
        $productCategoriesJsonPath = public_path('json/product_categories.json');
        $productCategories = json_decode(file_get_contents($productCategoriesJsonPath), true);

        return collect($productCategories['categories'])->sortBy('name')->values()->toArray();
    }

    public function render()
    {
        return view('livewire.Product.product-form-modal', [
            'categories' => $this->getCategories()
        ]);
    }
}
