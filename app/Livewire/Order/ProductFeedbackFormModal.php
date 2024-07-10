<?php

namespace App\Livewire\Order;

use App\Models\OrderedItem;
use App\Models\Product;
use App\Models\ProductFeedback;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ProductFeedbackFormModal extends Component
{
    use WireUiActions;

    public $orderedItem = null;
    public $product = null;
    public $feedbackContent;
    public $productRating;

    protected $listeners = [
        'open-product-feedback' => 'getData',
        'clearProductFeedbackForModalData' => 'clearData'
    ];

    public function getData($id){
        $this->orderedItem = OrderedItem::findOrFail($id);

        if($this->orderedItem){
            $this->product = $this->orderedItem->product;
        }
    }

    public function clearData(){
        $this->reset([
            'feedbackContent',
            'productRating'
        ]);

        $this->orderedItem = null;
        $this->product = null;
    }

    public function store(){
        $validated = $this->validate([
            'feedbackContent' => 'required',
            'productRating' => 'required',
        ]);

        try{
            $store = $this->storeFeedback($validated);

            if($store){
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Feedback successfully added.',
                ]);
    
                $this->dispatch('refresh-order-container', ['id' => $this->orderedItem->order->id]);
                $this->dispatch('close-modal', ['modal' => 'productFeedbackFormModal']);
                $this->clearData();
            }else{
                $this->notification()->send([
                    'icon' => 'error',
                    'title' => 'Error!',
                    'description' => 'Woops, its an error. There seems to be a problem inserting your feedback.',
                ]);
            }
        }catch(\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => $e->getMessage(),
            ]);
        }
    }

    public function storeFeedback($validated){
        return ProductFeedback::create([
            'product_id' => $this->product->id,
            'user_id' => Auth::id(),
            'content' => $validated['feedbackContent'],
            'rating' => $validated['productRating'],
        ]);
    }

    public function render()
    {
        return view('livewire.Order.product-feedback-form-modal');
    }
}
