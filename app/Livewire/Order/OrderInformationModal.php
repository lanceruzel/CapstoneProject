<?php

namespace App\Livewire\Order;

use App\Enums\Status;
use App\Models\Order;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class OrderInformationModal extends Component
{
    use WireUiActions;

    public $order = null;

    public $orderNumber;
    public $name;
    public $address;
    public $postal;
    public $contact;
    public $paymentMethod;
    public $paymentStatus;
    public $total;
    public $affiliateCode;
    public $products;

    public $trackingNumber;
    public $courrier;

    public $listOfCourriers = null;

    protected $listeners = [
        'view-order-info' => 'getData',
        'clearOrderViewModalData' => 'clearData'
    ];

    public function getData($id){
        $this->order = Order::findOrFail($id);

        if($this->order){
            $this->products = $this->order->orderedItems;
        }
    }

    public function mount(){
        $this->listOfCourriers = $this->getCourriers();
    }

    public function getCourriers() {
        $courriersJsonFile = public_path('json/courriers.json');
        $courriers = json_decode(file_get_contents($courriersJsonFile), true);

        sort($courriers);
    
        return $courriers; 
    }

    public function declineConfirmation(){
        $this->notification()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Decline the order?',
            'acceptLabel' => 'Yes, decline it',
            'method' => 'declineOrder',
        ]);
    }

    public function declineOrder(){
        $this->order->status = Status::OrderSellerCancel;
        $this->saveOrder();
    }

    public function acceptOrder(){
        //Decrease stocks
        foreach($this->products as $product){
            foreach(json_decode($product->product->variations) as $variation){
                if($variation->name == $product->variation){
                    $variation->stocks -= $product->quantity;
                }
            }
        }

        // Save modified variations back to product
        foreach ($this->products as $product) {
            $productVariations = json_decode($product->product->variations);
            foreach ($productVariations as &$variation) {
                if ($variation->name == $product->variation) {
                    $variation->stocks -= $product->quantity;
                }
            }
            // Convert back to JSON and update the product
            $product->product->variations = json_encode($productVariations);
            $product->product->save();
        }

        $this->order->status = Status::OrderSellerPreparing;
        $this->saveOrder();
    }

    public function updateTrackingNumber(){
        $validated = $this->validate([
            'trackingNumber' => 'required',
            'courrier' => 'required',
        ]);

        $this->order->status = Status::OrderSellerShipped;
        $this->order->tracking_number = $validated['trackingNumber'];
        $this->order->courrier = $validated['courrier'];
        $this->saveOrder();
    }

    public function saveOrder(){
        if($this->order->save()){
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Success!',
                'description' => 'Order successfully updated.',
            ]);

            $this->dispatch('refresh-order-table');
            $this->dispatch('close-modal', ['modal' => 'orderViewModal']);
        }else{
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error. There seems to be a problem updating this order status.',
            ]);
        }
    }

    public function clearData(){
        $this->reset([
            'orderNumber',
            'name',
            'address',
            'postal',
            'contact',
            'paymentMethod',
            'paymentStatus',
            'total',
            'affiliateCode',
            'products',
        ]);

        $this->order = null;
        $this->products = null;
    }

    public function render()
    {
        return view('livewire.Order.order-information-modal');
    }
}
