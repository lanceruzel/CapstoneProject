<?php

namespace App\Livewire\Cart;

use App\Enums\Status;
use App\Models\CartItem;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class CartItemContainer extends Component
{
    use WireUiActions;

    public $cartItem;
    public $quantity;

    public $isForCheckout;

    public $price;
    public $exists = false;
    public $available = false;
    public $suspended = false;
    public $status = null;
    public $stocksAvailable = 0;

    public function mount($id) {
        $this->cartItem = CartItem::findOrFail($id);
        $this->quantity = $this->cartItem->quantity;
    
        $variations = json_decode($this->cartItem->product->variations);
        $variationFound = false;
    
        if ($this->cartItem->product->status == Status::Suspended) {
            $this->status = 'Currently Suspended';
        } else {
            foreach ($variations as $variation) {
                if (strtolower($variation->name) == strtolower($this->cartItem->variation)) {
                    $variationFound = true;
                    $this->stocksAvailable = $variation->stocks;
    
                    // Adjust quantity if it exceeds available stocks
                    if ($this->quantity > $variation->stocks) {
                        $this->cartItem->quantity = $variation->stocks;
                        $this->cartItem->save();
                    }
    
                    $this->price = $variation->price;

                    if ($variation->stocks == 0) {
                        $this->status = 'Stocks unavailable';
                    }
    
                    break; 
                }
            }
    
            if (!$variationFound) {
                $this->status = 'Variation not found';
            }
        }

        if ($this->status == null) {
            $this->isForCheckout = $this->cartItem->for_checkout == 1 ? true : false;
        } else {
            $this->cartItem->for_checkout = 0;
            $this->cartItem->save();
        }
    }
    

    public function addQuantity(){
        if($this->cartItem->quantity >= $this->stocksAvailable){
            return;
        }

        $this->cartItem->quantity++;
        $this->cartItem->save();
        $this->dispatch('update-totalCheckout');
    }

    public function minusQuantity(){
        if($this->cartItem->quantity >= 2){
            $this->cartItem->quantity--;
            $this->cartItem->save();
            $this->dispatch('update-totalCheckout');
        }
    }

    public function deleteCartItem(){
        if($this->cartItem->delete()){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Successfully removed from your cart.',
            ]);

            $this->dispatch('update-totalCheckout');
            $this->dispatch('refresh-cartContainer');
        }else{
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error. There seems to be a problem removing this item from your cart.',
            ]);
        }
    }

    public function toggleIsForCheckout(){
        $this->cartItem->for_checkout = ! $this->cartItem->for_checkout;
        $this->cartItem->save();
        $this->dispatch('update-totalCheckout');
    }
    
    public function render()
    {
        $this->quantity = $this->cartItem->quantity;
        return view('livewire.Cart.cart-item-container');
    }
}
