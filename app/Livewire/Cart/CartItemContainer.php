<?php

namespace App\Livewire\Cart;

use App\Models\CartItem;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class CartItemContainer extends Component
{
    use WireUiActions;

    public $cartItem;
    public $quantity;

    public $price;

    public function mount($id){
        $this->cartItem = CartItem::findOrFail($id);
        $this->quantity = $this->cartItem->quantity;
        
        $variations = json_decode($this->cartItem->product->variations);

        foreach($variations as $variation){
            if($variation->name == $this->cartItem->variation){
                $this->price = $variation->price;
            }
        }
    }

    public function addQuantity(){
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
                'title' => 'Error Notification!',
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
