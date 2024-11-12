<?php

namespace App\Livewire\Product;

use App\Classes\WordFilter;
use App\Enums\Status;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
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
    public $origin_country;
    public $origin_state;

    public $remarks;

    public $hasVariation = false;
    public $productUpdate = null;
    public $existingImagePath ;

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
        'viewProductInformation' => 'getData',
        'updatedCountry'
    ];

    public $url = "https://api.countrystatecity.in/v1/countries";
    public $countryOptions;
    public $stateOptions;
    public $countryData = [];

    public function getData($id){
        $this->variations = null;

        $this->productUpdate = Product::findOrFail($id);

        if ($this->productUpdate) {
            $this->images = json_decode($this->productUpdate->images);
            $this->existingImagePath = $this->images;
            $this->name = $this->productUpdate->name;
            $this->description = $this->productUpdate->description;
            $this->category = $this->productUpdate->category;
            $this->origin_country = $this->productUpdate->origin_country;
            $this->origin_state = $this->productUpdate->origin_state;

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

    public function mount(){
        $this->loadCountries();
    }

    public function loadCountries()
    {
        $response = Http::withHeaders([
            'X-CSCAPI-KEY' => env('COUNTRY_STATE_CITY_API_KEY')
        ])->get($this->url);

        if ($response->successful()) {
            $this->countryData = $response->json();

            $this->countryOptions = collect($this->countryData)->map(function ($country) {
                return [
                    'name' => $country['name'],
                    'value' => $country['name']
                ];
            })
            ->sortBy('name')
            ->values()
            ->toArray();
        } else {
            Log::error('Failed to load countries', ['response' => $response->body()]);
        }
    }

    public function updatedCountry(){
        $this->state = null;
        $this->stateOptions = [];
        $this->loadStates();
    }

    public function loadStates(){
        if(!$this->origin_country){
            return;
        }

        $selectedCountry = collect($this->countryData)->firstWhere('name', $this->origin_country);
        
        if(!$selectedCountry){
            Log::error('Selected country not found', ['country' => $this->origin_country]);
            return;
        }

        $response = Http::withHeaders([
            'X-CSCAPI-KEY' => env('COUNTRY_STATE_CITY_API_KEY')
        ])->get($this->url . '/' . $selectedCountry['iso2'] . '/states');

        if ($response->successful()) {
            $this->stateOptions = collect($response->json())->map(function ($state) {
                return [
                    'name' => $state['name'],
                    'value' => $state['name']
                ];
            })
            ->sortBy('name')
            ->values()
            ->toArray();
        } else {
            Log::error('Failed to load states', ['response' => $response->body()]);
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
                    'description' => $this->productUpdate == true ? 'Your product has been successfully updated.' : 'Your product has been successfully submitted for review.',
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

        $variations = null;

        if($this->hasVariation == true){

            if(count($this->variations) > 1){
                $variations = $this->variations;
            }else{
                $variations = [
                    0 => [
                        'name' => 'Default',
                        'stocks' => $this->variations[0]['stocks'],
                        'price' => $this->variations[0]['price'],
                    ]
                ];
            }
        }else{
            $variations = [
                0 => [
                    'name' => 'Default',
                    'stocks' => $validated['stocks'],
                    'price' => $validated['price'],
                ]
            ];
        }

        return Product::updateOrCreate(
            [
                'id' => $this->productUpdate ? $this->productUpdate->id : null,
                'seller_id' => Auth::id()
            ],
            [
                'name' => WordFilter::filteredInput($validated['name']),
                'category' => $validated['category'],
                'description' => WordFilter::filteredInput($validated['description']),
                'status' => $status,
                'origin_country' => $validated['origin_country'],
                'origin_state' => $validated['origin_state'],
                'images' => $this->productUpdate != null && json_decode($this->productUpdate->images) == $this->images ? json_encode($this->images) : $this->storeImages($this->images),
                'variations' => json_encode($variations)
            ]
        );
    }

    public function formValidate(){
        $rules = [
            'name' => 'required|min:10',
            'category' => 'required',
            'description' => 'required|min:150',
            'origin_country' => 'required',
            'origin_state' => 'required'
        ];

        if($this->productUpdate){
            if(empty($this->images) || !$this->images || $this->images == '[]'){
                $rules['images'] = 'required|image|mimes:png,jpg,jpeg';
            }else{
                if($this->images == $this->existingImagePath || array_intersect($this->images,$this->existingImagePath)){
                    $rules['images.*'] = '';
                }else{
                    $rules['images.*'] = 'required|image|mimes:png,jpg,jpeg';
                }
            }
        }else{
            $rules['images.*'] = 'required|image|mimes:png,jpg,jpeg';
        }

        if ($this->hasVariation) {
            //Check if there is empty variations
            $this->removeEmptyVariations();

            foreach($this->variations as $key => $i){
                $rules["variations.$key.name"] = 'required';

                if(!$this->productUpdate){
                    $rules["variations.$key.stocks"] = 'required|numeric|min:20|max:9999';
                }
                
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
            'remarks',
            'existingImagePath',
            'origin_country',
            'origin_state',
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
        $dbImages = [];

        if($images){
            if($this->productUpdate){
                $dbImages = json_decode($this->productUpdate->images, true) ?? [];
            }

            foreach($dbImages as $dbImage){
                if(!in_array($dbImage, $this->images)){
                    $imagePath = public_path('uploads/products/' . $dbImage);
    
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }

            foreach($images as $key => $image) {
                if($dbImages !== null && in_array($image, $dbImages)){
                    // Existing image, keep the path
                    array_push($imagePaths, $image);
                }else{
                    // New image, store and get path
                    $filename = $key . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                    // $image->storeAs('products', $filename);
                    array_push($imagePaths, $filename);

                    $img = ImageManager::gd()->read($image->getRealPath());
                    $img->contain(500, 400);

                    $img->save(public_path('uploads/products/' . $filename));
                }
            }
        }

        return json_encode($imagePaths);
    }

    public function deleteImage($index){
        array_splice($this->images, $index, 1);
    }

    public function render(){
        if($this->hasVariation && count($this->variations) == 1){
            $this->variations[1] =  [
                'name' => '',
                'stocks' => '',
                'price' => '',
            ];
        }

        return view('livewire.Product.product-form-modal', [
            'categories' => $this->getCategories()
        ]);
    }
}
