<?php

namespace App\Livewire\Return;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Models\Order;
use App\Models\Product;
use App\Models\ReturnRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use WireUi\Traits\WireUiActions;

class ReturnProductFormModal extends Component
{
    use WireUiActions;
    use WithFileUploads;

    public $images;
    public $content;

    public $order;
    public $orderedProducts = [];
    public $selectedProducts = [];

    protected $listeners = [
        'clearProductReturnFormModalData' => 'clearData',
        'openReturnRequestForm' => 'getData'
    ];

    public function getData($id){
        $this->order = Order::findOrFail($id);
        
        if($this->order){
            $this->orderedProducts = $this->order->orderedItems->map(function ($item) {
                return [
                    'id' => ['id' => $item->product->id, 'name' => $item->product->name],
                    'name' => $item->product->name,
                ];
            })->toArray();
        }
    }

    public function clearData(){
        $this->reset([
            'images',
            'content',
            'order',
        ]);

        $this->selectedProducts = [];
        $this->orderedProducts = [];
    }

    public function sendRequest(){
        $validated = $this->validateForm();

        try{
            $store = $this->storeReturnRequest($validated);

            if($store){
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success Notification!',
                    'description' => 'Your return request has been sent.',
                ]);

                $this->dispatch('close-modal', ['modal' => 'productReturnFormModal']);
                $this->dispatch('refresh-order-container', ['id' => $this->order->id]);

                UserNotif::sendNotif($this->order->seller_id, 'Your have received a return request.' , NotificationType::ReturnRequest);
            }else{
                $this->notification()->send([
                    'icon' => 'error',
                    'title' => 'Error!',
                    'description' => 'Woops, its an error. There seems to be a problem sending your request.',
                ]);
            }
        }catch(\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error. ' . $e->getMessage(),
            ]);
        }
    }

    public function validateForm(){
        return $this->validate([
            'content' => 'required|min:10',
            'images.*' => 'image|mimes:png,jpg,jpeg',
            'selectedProducts' => 'required'
        ]);
    }

    public function storeReturnRequest($validated){
        return ReturnRequest::create([
            'reporter_id' => Auth::id(),
            'seller_id' => $this->order->seller_id,
            'order_id' => $this->order->id,
            'products' => json_encode($this->selectedProducts),
            'content' => $validated['content'],
            'images' => json_encode($this->storeImages($this->images)),
            'status' => Status::ReturnRequestReview
        ]);
    }

    public function deleteImage($index){
        array_splice($this->images, $index, 1);
    }

    public function storeImages($images){
        $imagePaths = [];

        if($images){
            foreach ($images as $key => $image) {
                // New image, store and get path
                $filename = $key . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('return', $filename);
                array_push($imagePaths, $filename);
            }
        }

        return $imagePaths;
    }

    public function render(){
        return view('livewire.Return.return-product-form-modal');
    }
}
