<?php

namespace App\Livewire\Product;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

            if(count($this->variations) == 1){
                $this->selectedVariation = $this->variations[0]->name;
            }
        }
    }

    public function addToCart(){
        $validated = $this->validate([
            'quantity' => 'required',
        ]);

        if($this->selectedVariation == null){
            $this->notification()->send([
                'icon' => 'info',
                'title' => 'Info!',
                'description' => 'Please select a variation.',
            ]);
            return;
        }

        if(!$this->isStockAvailable()){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Insufficient Stock',
                'description' => 'The selected quantity exceeds available stock.',
            ]);
            return;
        }

        try{
            $existingCartItem = CartItem::where('user_id', Auth::id())->where('product_id', $this->product->id)->where('variation', $this->selectedVariation)->first();

            if($existingCartItem){
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Added to your cart.',
                ]);
    
                $existingCartItem->increment('quantity', $this->quantity);
    
                $this->dispatch('close-modal', ['modal' => 'variationSelectionModal']);
                // $this->dispatch('close-modal', ['modal' => 'productViewModal']);
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
            // $this->dispatch('close-modal', ['modal' => 'productViewModal']);
        }catch(\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error.',
            ]);

            Log::error('Error AffiliateInvite: ' . $e->getMessage());
        }
    }

    public function isStockAvailable(){
        if($this->selectedVariation){
            foreach($this->variations as $variation){
                if($variation->name === $this->selectedVariation && $variation->stocks >= $this->quantity){
                    return true;
                }
            }
        }
        return false;
    }

    public function clearData(){
        $this->variations = null;
        $this->selectedVariation = null;
        $this->quantity = 1;
    }

    public function render()
    {
        return view('livewire.Product.product-view-variation-selection-modal');
    }
}
