<?php

namespace App\Livewire\ProductRegistration;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Models\Product;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ProductRegistrationModal extends Component
{
    use WireUiActions;

    public $images;
    public $name;
    public $description;
    public $category;
    public $stocks;
    public $price;

    public $hasVariation = false;

    public $variations = [];

    public $product;

    public $remarks;

    protected $listeners = [
        'viewProductRegistration' => 'getData',
    ];

    public function getData($id){
        $this->variations = null;

        $this->product = Product::findOrFail($id);

        if ($this->product) {
            $this->images = json_decode($this->product->images);
            $this->name = $this->product->name;
            $this->description = $this->product->description;
            $this->category = $this->product->category;

            $this->variations = json_decode($this->product->variations, true);

            $this->remarks = $this->product->remarks;

            if (count($this->variations) >= 2) {
                $this->hasVariation = true;
            } else {
                $this->price = $this->variations[0]['price'];
                $this->stocks = $this->variations[0]['stocks'];
            }
        }
    }

    public function store($status){
        if($status == 'accepted'){
            $this->product->status = Status::Available;
        }else{
            $validated = $this->validate([
                'remarks' => 'required|min:10'
            ]);

            $this->product->status = Status::ForReSubmission;
            $this->product->remarks = $validated['remarks'];
        }

        if ($this->product->save()) {
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'This product registration has been updated!.',
            ]);

            $this->dispatch('refresh-product-registrations-table');
            $this->dispatch('close-modal', ['modal' => 'productRegistrationModal']);

            UserNotif::sendNotif($this->product->seller_id, $this->product->name . '\'s registration status has been updated.' , NotificationType::ProductRegistration);
        } else {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, there\'s an error updating this product\'s status.',
            ]);
        }
    }

    public function getCategories(){
        $productCategoriesJsonPath = public_path('json/product_categories.json');
        $productCategories = json_decode(file_get_contents($productCategoriesJsonPath), true);

        return collect($productCategories['categories'])->sortBy('name')->values()->toArray();
    }

    public function render()
    {
        return view('livewire.ProductRegistration.product-registration-modal', [
            'categories' => $this->getCategories()
        ]);
    }
}
