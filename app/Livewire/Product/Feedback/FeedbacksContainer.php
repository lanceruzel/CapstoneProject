<?php

namespace App\Livewire\Product\Feedback;

use App\Models\ProductFeedback;
use Livewire\Component;

class FeedbacksContainer extends Component
{
    public $id;

    public function mount($id){
        $this->id = $id;
    }

    protected $listeners = [
        'new-feedback' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.Product.Feedback.feedbacks-container', [
            'feedbacks' => ProductFeedback::where('product_id', $this->id)->orderBy('id', 'desc')->get(),
        ]);
    }
}
