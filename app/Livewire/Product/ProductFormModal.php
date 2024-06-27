<?php

namespace App\Livewire\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use WireUi\Traits\WireUiActions;

class ProductFormModal extends Component
{
    use WithFileUploads;
    use WireUiActions;

    public $images;
    public $name;
    public $description;
    public $category;
    public $stocks;
    public $price;

    public $hasVariation = false;

    public $isUpdate = false;
    public $productUpdate = null;

    public $variations = [
        0 => [
            'name' => '',
            'stocks' => '',
            'price' => '',
        ],
        1 => [
            'name' => '',
            'stocks' => '',
            'price' => '',
        ]
    ];

    protected $listeners = [
        'clearProductFormModalData' => 'clearData',
        'viewProductInformation' => 'getData'
    ];

    public function getData($id){
        $this->isUpdate = true;

        $this->productUpdate = Product::findOrFail($id);

        if ($this->productUpdate) {
            $this->images = json_decode($this->productUpdate->images);
            $this->name = $this->productUpdate->name;
            $this->description = $this->productUpdate->description;
            $this->category = $this->productUpdate->category;

            $this->variations = json_decode($this->productUpdate->variations);

            if (count($this->variations) >= 2) {
                $this->hasVariation = true;
            } else {
                $this->price = $this->variations[0]->price;
                $this->stocks = $this->variations[0]->stocks;
            }
        }
    }

    public function store(){
        $validated = $this->formValidate();

        try {
            $storeProduct = $this->storeProduct($validated);

            if ($storeProduct) {
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Your product has been successfully submitted for review.',
                ]);

                $this->dispatch('refresh-product-table');
                $this->dispatch('close-modal', ['modal' => 'productFormModal']);
            } else {
                $this->notification()->send([
                    'icon' => 'error',
                    'title' => 'Error!',
                    'description' => 'Woops, there\'s an error while submitting your product.',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error store product: ' . $e->getMessage());

            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, there\'s an error while submitting your product.',
            ]);
        }
    }

    public function storeProduct($validated){
        return Product::updateOrCreate(
            [
                'id' => $this->productUpdate ? $this->productUpdate->id : null,
                'seller_id' => Auth::id()
            ],
            [
                'name' => $validated['name'],
                'category' => $validated['category'],
                'description' => $validated['description'],
                'images' => $this->productUpdate != null && json_decode($this->productUpdate->images) == $this->images ? json_encode($this->images) : $this->storeImages($this->images),
                'variations' => $this->hasVariation == true ? json_encode($this->variations) : json_encode([
                    0 => [
                        'name' => 'Default',
                        'stocks' => $validated['stocks'],
                        'price' => $validated['price'],
                    ]
                ])
            ]
        );
    }

    public function formValidate(){
        $rules = [
            'name' => 'required|min:10',
            'category' => 'required',
            'description' => 'required|min:50',
            'images.*' => $this->productUpdate != null ? '' : 'required|image|mimes:png,jpg,jpeg',
        ];

        if ($this->hasVariation) {
            $rules['variations.*.name'] = 'required';
            $rules['variations.*.stocks'] = 'required|numeric|min:20|max:9999';
            $rules['variations.*.price'] = 'required|numeric';
        } else {
            $rules['stocks'] = 'required|numeric|min:20|max:9999';
            $rules['price'] = 'required|numeric';
        }

        return $this->validate($rules);
    }

    public function clearData(){
        $this->reset([
            'images',
            'name',
            'description',
            'category',
            'stocks',
            'price'
        ]);

        $this->hasVariation = false;

        $this->variations = [
            0 => [
                'name' => '',
                'stocks' => '',
                'price' => '',
            ],
            1 => [
                'name' => '',
                'stocks' => '',
                'price' => '',
            ]
        ];

        $this->productUpdate = null;
        $this->isUpdate = false;
    }

    public function addVariation(){
        $this->variations[] = [
            'name' => '',
            'stocks' => '',
            'price' => '',
        ];

        Log::info($this->variations);
    }

    public function removeVariation($variationKey){
        if (isset($this->variations[$variationKey])) {
            array_splice($this->variations, $variationKey, 1);
            $this->variations = array_values($this->variations);
        }
    }

    public function getCategories(){
        $productCategoriesJsonPath = public_path('json/product_categories.json');
        $productCategories = json_decode(file_get_contents($productCategoriesJsonPath), true);

        return collect($productCategories['categories'])->sortBy('name')->values()->toArray();
    }

    public function storeImages($images){
        $imagePaths = [];
        $dbImages = null;

        if($images){

            if($this->productUpdate){
                $dbImages = json_decode($this->productUpdate->images, true); // Decode to array
            }

            foreach ($images as $key => $image) {
                if ($dbImages !== null && in_array($image, $dbImages)) {
                    // Existing image, keep the path
                    array_push($imagePaths, $image);
                } else {
                    // New image, store and get path
                    $filename = $key . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->storeAs('products', $filename);
                    array_push($imagePaths, $filename);
                }
            }
        }

        return json_encode($imagePaths);
    }

    public function deleteImage($index){
        array_splice($this->images, $index, 1);
    }

    public function render(){
        return view('livewire.Product.product-form-modal', [
            'categories' => $this->getCategories()
        ]);
    }
}
