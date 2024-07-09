<?php

namespace App\Livewire\Checkout\TotalSummary;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderedItem;
use App\Models\UserShippingInformation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class OrderTotalSummary extends Component
{
    use WireUiActions;

    public $merchandiseTotal = 0;
    public $shippingTotal = 150;

    public $shippingInformation = null;

    protected $listeners = [
        'payment-completed' => 'placeOrder',
        'selected-shipping-address' => 'getShippingInformation'
    ];

    public function getShippingInformation($id){
        $this->shippingInformation = UserShippingInformation::findOrFail($id);
    }

    public function placeOrder($status = null){
        //Check if user haven't select shipping information
        if($this->shippingInformation == null){
            $this->notification()->send([
                'icon' => 'info',
                'title' => 'Info!',
                'description' => 'Please select shipping information first.',
            ]);

            return;
        }

        $checkedOutSellers = CartItem::groupBySellerCheckout();

        $paymentMethod = 'COD';
        $isPaid = false;

        if($status && $status == 'COMPLETED'){
            $paymentMethod = 'Paypal';
            $isPaid = true;
        }

        foreach($checkedOutSellers as $checkedOutSeller){
            $seller = $checkedOutSeller['seller'];
            $products = $checkedOutSeller['products'];
            $total = $checkedOutSeller['total'];
            $shippingInformation = $this->shippingInformation[0];

            try{
                $storeOrder = $this->storeOrder($seller, $shippingInformation, $paymentMethod, $isPaid, $total);

                if($storeOrder){
                    //Store Ordered Product
                    foreach($products as $product){
                        $storeOrderedProduct = $this->storeOrderedProducts($storeOrder->id, $product);

                        if(!$storeOrderedProduct){
                            $this->notification()->send([
                                'icon' => 'error',
                                'title' => 'Error!',
                                'description' => 'Woops, theres an error submitting your ordered products.',
                            ]);

                            return;
                        }
                    }

                    //Delete Cart Items
                    if(CartItem::deleteCheckoutItems()){
                        $this->notification()->send([
                            'icon' => 'error',
                            'title' => 'Error!',
                            'description' => 'Woops, there\'s a problem removing your cart items',
                        ]);

                        return;
                    }
                }

            }catch(\Exception $e){
                $this->notification()->send([
                    'icon' => 'error',
                    'title' => 'Error!',
                    'description' => 'Woops, its an error. ' . $e->getMessage() ,
                ]);

                return;
            }
        }

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success!',
            'description' => 'Your order/s has been successfully placed.',
        ]);

        return redirect()->route('orders');
    }

    public function storeOrder($seller, $shippingInformation, $paymentMethod, $isPaid, $total){
        return Order::create([
            'user_id' => Auth::id(),
            'seller_id' => $seller->id,
            'name' => $shippingInformation['full_name'],
            'address' => $shippingInformation['address_1'] . ' ' . $shippingInformation['address_2'],
            'postal' => $shippingInformation['postal'],
            'contact' => $shippingInformation['phone_number'],
            'total' => $total,
            'payment_method' => $paymentMethod,
            'is_paid' => $isPaid
        ]);
    }

    public function storeOrderedProducts($orderId, $product){
        return OrderedItem::create([
            'order_id' => $orderId,
            'product_id' => $product['product_id'],
            'variation' => $product['variation'],
            'quantity' => $product['quantity'],
            'subtotal' => $product->getTotal()
        ]);
    }

    public function getMerchandiseTotal(){
        $total = 0;

        foreach(CartItem::groupBySellerCheckout() as $item){
            $total += $item['total'];
        }

        return $total;
    }

    public function render(){
        $this->merchandiseTotal = $this->getMerchandiseTotal();

        return view('livewire.Checkout.TotalSummary.order-total-summary', [
            'merchandiseTotal' => $this->merchandiseTotal,
            'shippingTotal' => $this->shippingTotal
        ]);
    }
}
