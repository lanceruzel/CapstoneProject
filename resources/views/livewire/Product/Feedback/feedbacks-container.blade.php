<div class="space-y-3 divide-y-2">
    @if(count($feedbacks) > 0)
        @foreach ($feedbacks as $feedback)
            <livewire:Product.Feedback.feedback-container :feedback="$feedback" wire:key="feedback-{{ $feedback->id }}">
        @endforeach
    @else
        <div class="bg-gray-100 w-full text-center rounded-lg py-3 my-2">
            No feedbacks yet
        </div>
    @endif
</div>