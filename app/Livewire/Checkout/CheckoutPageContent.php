<?php

namespace App\Livewire\Checkout;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Models\Affiliate;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderedItem;
use App\Models\UserShippingInformation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class CheckoutPageContent extends Component
{
    use WireUiActions;

    public $shippingInformation = null;
    public $checkedOutSellers = null;

    public $merchandiseTotal = 0;
    public $shippingTotal = 150;

    public $affiliate = [];

    protected $listeners = [
        'selected-shipping-address' => 'getShippingInformationData',

    ];

    public function mount(){
        $this->checkedOutSellers = CartItem::groupBySellerCheckout();

        $this->merchandiseTotal = $this->getMerchandiseTotal();

        foreach ($this->checkedOutSellers as $checkedOutSeller) {
            $this->affiliate[$checkedOutSeller['seller']->id] = null; // Initialize the affiliate codes
        }
    }

    public function getShippingInformationData($id){
        $this->shippingInformation = UserShippingInformation::findOrFail($id);
    }

    public function validateAffiliateInputs(){
        $rules = [];

        foreach($this->checkedOutSellers as $checkedOutSeller){
            $rules["affiliate." . $checkedOutSeller['seller']->id] = 'exists:affiliates,affiliate_code|min:10|max:15';
        } 

        return ($rules);
    }

    public function checkCodePerStore(){
        foreach($this->checkedOutSellers as $checkedOutSeller){
            $seller = $checkedOutSeller['seller'];

            if($this->affiliate[$seller->id] != null){
                if(!$this->checkIfSellerHasAffiliateCode($seller->id, $this->affiliate[$seller->id])){
                    $this->addError("affiliate." . $seller->id, 'This affiliate code does not exists or this affiliate code is currently inactive.');
                    return false;
                }
            }
            
        }

        return true;
    }
    
    public function placeOrder($status = null){
        $this->validateAffiliateInputs();

        if (!$this->checkCodePerStore()) {
            return; // Stop further execution if the affiliate code check fails
        }

        //Check if user haven't select shipping information
        if($this->shippingInformation == null){
            $this->notification()->send([
                'icon' => 'info',
                'title' => 'Info!',
                'description' => 'Please select shipping information first.',
            ]);

            return;
        }

        $paymentMethod = 'COD';
        $isPaid = false;

        if($status && $status == 'COMPLETED'){
            $paymentMethod = 'Paypal';
            $isPaid = true;
        }

        //Per Seller
        foreach($this->checkedOutSellers as $checkedOutSeller){
            $seller = $checkedOutSeller['seller'];
            $products = $checkedOutSeller['products'];
            $total = $checkedOutSeller['total'];
            $shippingInformation = $this->shippingInformation[0];

            $commission = 0;

            if($this->affiliate[$seller->id] != ''){
                $rates = Affiliate::where('affiliate_code', $this->affiliate[$seller->id])
                        ->where('status', Status::Active)
                        ->pluck('rate');

                $rate = floatval($rates[0]) / 100; 

                $commission = $total * $rate;
            }

            try{
                $storeOrder = $this->storeOrder($seller, $shippingInformation, $paymentMethod, $isPaid, $total, $this->affiliate[$seller->id], $commission);

                if($storeOrder){
                    //Notify Seller
                    UserNotif::sendNotif($seller->id, 'You have new order.' , NotificationType::Order);

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

    public function storeOrder($seller, $shippingInformation, $paymentMethod, $isPaid, $total, $code = null, $commission = null){
        return Order::create([
            'user_id' => Auth::id(),
            'seller_id' => $seller->id,
            'name' => $shippingInformation['full_name'],
            'address' => $shippingInformation['address_1'] . ' ' . $shippingInformation['address_2'],
            'postal' => $shippingInformation['postal'],
            'contact' => $shippingInformation['phone_number'],
            'total' => $total,
            'payment_method' => $paymentMethod,
            'is_paid' => $isPaid,
            'affiliate_code' => $code,
            'commission' => $commission
        ]);
    }

    public function checkIfSellerHasAffiliateCode($id, $code){
        return Affiliate::where('store_id', $id)->where('affiliate_code', $code)->where('status', Status::Active)->exists();
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
        $this->dispatch('getTotal', ['total' => ($this->merchandiseTotal + $this->shippingTotal)]);

        return view('livewire.Checkout.checkout-page-content', [
            'merchandiseTotal' => $this->merchandiseTotal,
            'shippingTotal' => $this->shippingTotal,
            'checkedOutSellers' => $this->checkedOutSellers
        ]);
    }
}
