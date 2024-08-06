<?php

namespace App\Livewire\Return;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Models\Order;
use App\Models\OrderedItem;
use App\Models\Product;
use App\Models\ReturnRequest;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ReturnCreateOrderModal extends Component
{
    use WireUiActions;

    public $order;
    public $request;
    public $requestedItems;

    protected $listeners = [
        'return-create-order-data' => 'getData',
        'clearReturnCreateOrderModalData' => 'clearData'
    ];

    public function getData($orderId, $requestId){
        $this->order = Order::findOrFail($orderId);
        $this->request = ReturnRequest::findOrFail($requestId);

        $this->requestedItems = $this->getRequestedItems();
    }

    public function clearData(){
        $this->reset([
            'order',
            'request',
            'requestedItems'
        ]);
    }

    public function incrementQuantity($index){
        if(isset($this->requestedItems[$index])){
            $this->requestedItems[$index]['quantity']++;
        }
    }

    public function decrementQuantity($index){
        if(isset($this->requestedItems[$index]) && $this->requestedItems[$index]['quantity'] > 1){
            $this->requestedItems[$index]['quantity']--;
        }
    }

    public function getRequestedItems(){
        $items = [];
        $requestedItems = json_decode($this->request->products);

        foreach($requestedItems as $requested){
            $product = Product::find($requested->id);
            if($product){
                $items[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'variation' => json_decode($product->variations),
                    'selectedVariation' => '',
                    'quantity' => 1,
                ];
            }
        }

        return $items;
    }

    public function createOrder(){
        $this->formValidate();

        if($this->order){
            $order = $this->storeOrder($this->order);

            if($order){
                foreach($this->requestedItems as $index => $item){
                    $storeOrderedProduct = $this->storeOrderedProducts($order->id, $this->requestedItems[$index]);
    
                    if(!$storeOrderedProduct){
                        $this->notification()->send([
                            'icon' => 'error',
                            'title' => 'Error!',
                            'description' => 'Woops, theres an error submitting your ordered products.',
                        ]);
        
                        return;
                    }
                }
    
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Order has successfully created.',
                ]);
    
                $this->request->status = Status::ReturnRequestSellerOrderCreated;
                $this->request->save();

                $this->dispatch('close-modal', ['modal' => 'returnCreateOrderModal']);
                $this->dispatch('close-modal', ['modal' => 'viewReturnRequestModal']);
                $this->dispatch('refresh-return-product-table');
                UserNotif::sendNotif($this->order->user_id, 'Your return request has been fulfilled.' , NotificationType::ReturnRequest);
            }
        }
    }

    public function formValidate(){
        $rules = [];

        foreach($this->requestedItems as $key => $i){
            $rules["requestedItems.$key.selectedVariation"] = 'required';
        }   

        return $this->validate($rules);
    }

    public function storeOrderedProducts($orderId, $product){
        return OrderedItem::create([
            'order_id' => $orderId,
            'product_id' => $product['id'],
            'variation' => $product['selectedVariation'],
            'quantity' => $product['quantity'],
            'subtotal' => '0'
        ]);
    }

    public function storeOrder($order){
        return Order::create([
            'user_id' => $order->user_id,
            'seller_id' => $order->seller_id,
            'name' => $order->name,
            'address' => $order->address,
            'postal' => $order->postal,
            'contact' => $order->contact,
            'total' => 0,
            'payment_method' => 'Paypal',
            'is_paid' => true,
        ]);
    }

    public function render()
    {
        return view('livewire.Return.return-create-order-modal');
    }
}
