<?php

namespace App\Livewire\Product;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ProductViewVariationSelectionModal extends Component
{
    use WireUiActions;

    public $variations = null;

    public $selectedVariation = null;

    public $quantity = 1;

    public $product;

    protected $listeners = [
        'clearVariationSelectionData' => 'clearData',
        'view-variations-info' => 'getData'
    ];

    public function getData($id){
        $this->product = Product::findOrFail($id);
        
        if($this->product){
            $this->variations = json_decode($this->product->variations);
        }
    }

    public function addToCart(){
        $validated = $this->validate([
            'quantity' => 'required',
        ]);

        $existingCartItem = CartItem::where('user_id', Auth::id())->where('product_id', $this->product->id)->where('variation', $this->selectedVariation)->first();

        if($existingCartItem){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Added to your cart.',
            ]);

            $existingCartItem->increment('quantity', $this->quantity);

            $this->dispatch('close-modal', ['modal' => 'variationSelectionModal']);
            $this->dispatch('close-modal', ['modal' => 'productViewModal']);
            return;
        }

        $store = CartItem::create([
            'user_id' => Auth::id(),
            'seller_id' => $this->product->seller_id,
            'product_id' => $this->product->id,
            'variation' => $this->selectedVariation,
            'quantity' => $validated['quantity'],
        ]);

        if($store){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Added to your cart.',
            ]);
        }else{
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error Notification!',
                'description' => 'Woops, its an error. There seems to be a problem inserting this product to your cart.',
            ]);
        }

        $this->dispatch('close-modal', ['modal' => 'variationSelectionModal']);
        $this->dispatch('close-modal', ['modal' => 'productViewModal']);
    }

    public function clearData(){
        $this->variations = null;
    }

    public function render()
    {
        return view('livewire.Product.product-view-variation-selection-modal');
    }
}
