<?php

namespace App\Livewire\Product\Feedback;

use Livewire\Component;

class FeedbackContainer extends Component
{
    public $feedback;

    public function mount($feedback){
        $this->feedback = $feedback;
    }

    public function render()
    {
        return view('livewire.Product.Feedback.feedback-container', [
            'feedback' => $this->feedback
        ]);
    }
}
