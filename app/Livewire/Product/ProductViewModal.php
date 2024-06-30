<?php

namespace App\Livewire\Product;

use App\Enums\Status;
use App\Models\CartItem;
use App\Models\OrderedItem;
use App\Models\Product;
use App\Models\ProductFeedback;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ProductViewModal extends Component
{
    use WireUiActions;

    public $product;
    public $quantity = 1;
    public $images;
    public $name;
    public $description;
    public $allowFeedback = false;

    public $variations;

    protected $listeners = [
        'view-product-info' => 'getData',
        'new-feedback' => '$refresh',
    ];

    public function getData($id, $feedback = false){
        $this->product = Product::findOrFail($id);
        $this->images = json_decode($this->product->images);

        $this->allowFeedback = $feedback;

        $this->variations = json_decode($this->product->variations);
    }

    public function store_toCart(){
        $validated = $this->validate([
            'quantity' => 'required',
        ]);

        $existingCartItem = CartItem::where('user_id', Auth::id())->where('product_id', $this->product->id)->where('variation', 'Default')->first();

        if($existingCartItem){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Added to your cart.',
            ]);

            $existingCartItem->increment('quantity', 1);
            return;
        }

        $store = CartItem::create([
            'user_id' => Auth::id(),
            'seller_id' => $this->product->seller_id,
            'product_id' => $this->product->id,
            'variation' => 'Default',
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
    }

    public function render()
    {
        $hasFeedback = false;
        $hasOrderedItem = false;

        if($this->product){
            $feedbackExists = ProductFeedback::where('user_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->exists();

            $userOrderItems = OrderedItem::where('product_id', $this->product->id)->get();
            
            if($userOrderItems){
                foreach ($userOrderItems as $orderItem) {
                    $order = $orderItem->order;

                    if($order->status == Status::Delivered && $order->user_id == Auth::id()){
                        $hasOrderedItem = true;
                    }
                }
            }
            
            if($feedbackExists){
                $hasFeedback = true;
            }
        }

        return view('livewire.Product.product-view-modal', [
            'product' => $this->product,
            'hasFeedback' => $hasFeedback,
            'hasOrderedItem' => $hasOrderedItem
        ]);
    }
}
