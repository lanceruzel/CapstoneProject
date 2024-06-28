<?php

namespace App\Livewire\Product;

use App\Enums\Status;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use NunoMaduro\Collision\Adapters\Phpunit\State;
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

    public $remarks;

    public $hasVariation = false;
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
        $this->variations = null;

        $this->productUpdate = Product::findOrFail($id);

        if ($this->productUpdate) {
            $this->images = json_decode($this->productUpdate->images);
            $this->name = $this->productUpdate->name;
            $this->description = $this->productUpdate->description;
            $this->category = $this->productUpdate->category;

            $this->remarks = $this->productUpdate->remarks;

            $this->variations = json_decode($this->productUpdate->variations, true);

            if (count($this->variations) >= 2) {
                $this->hasVariation = true;
            } else {
                $this->price = $this->variations[0]['price'];
                $this->stocks = $this->variations[0]['stocks'];
            }
        }
    }

    public function removeEmptyVariations(){
        $filteredVariations = [];
        foreach ($this->variations as $variation) {
            $hasNonEmptyValue = false;
            
            foreach ($variation as $value) {
                if (!empty(trim($value))) {
                    $hasNonEmptyValue = true;
                    break; // Exit inner loop if a non-empty value is found
                }
            }

            if ($hasNonEmptyValue) {
                $filteredVariations[] = $variation;
            }
        }
        $this->variations = $filteredVariations;
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
        $status = 'for-review';
        
        if($this->productUpdate){
            if($this->productUpdate->status == Status::ForReSubmission){
                $status = Status::ForReview;
            }else{
                $status = $this->productUpdate->status;
            }
        }

        return Product::updateOrCreate(
            [
                'id' => $this->productUpdate ? $this->productUpdate->id : null,
                'seller_id' => Auth::id()
            ],
            [
                'name' => $validated['name'],
                'category' => $validated['category'],
                'description' => $validated['description'],
                'status' => $status,
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
            //Check if there is empty variations
            $this->removeEmptyVariations();

            foreach($this->variations as $key => $i){
                $rules["variations.$key.name"] = 'required';
                $rules["variations.$key.stocks"] = 'required|numeric|min:20|max:9999';
                $rules["variations.$key.price"] = 'required|numeric';
            }   
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
            'price',
            'remarks'
        ]);


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
        $this->hasVariation = false;
    }

    public function addVariation(){
        $this->variations[] = [
            'name' => '',
            'stocks' => '',
            'price' => '',
        ];
    }

    public function removeVariation($variationKey){
        array_splice($this->variations, $variationKey, 1);
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
