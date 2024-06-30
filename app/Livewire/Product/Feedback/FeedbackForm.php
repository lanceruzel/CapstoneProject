<?php

namespace App\Livewire\Product\Feedback;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FeedbackForm extends Component
{
    public $feedbackContent;
    public $productRating;
    public $id;

    protected $listeners = [
        'for-feedback' => 'getData',
    ];

    public function getData($data){
        $this->id = $data['id'];
    }

    public function store_feedback(){
        $validated = $this->validate([
            'feedbackContent' => 'required',
            'productRating' => 'required',
        ]);

        $store = Product::create([
            'product_id' => $this->id,
            'user_id' => Auth::id(),
            'content' => $validated['feedbackContent'],
            'rating' => $validated['productRating'],
        ]);

        if($store){
            $this->dispatch('create-toast', [
                'status' => 'success',
                'message' => 'Your feedback has been successfully posted.'
            ]);

            $this->dispatch('new-feedback');
            $this->reset('feedbackContent');
            $this->reset('productRating');
        }
    }

    public function render()
    {
        return view('livewire.Product.Feedback.feedback-form');
    }
}
