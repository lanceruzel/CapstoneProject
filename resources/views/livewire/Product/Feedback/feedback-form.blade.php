<form class="space-y-3 w-full">
    <div class="w-32">
        <x-input label="Rating" placeholder="0.0 - 5.0" shadowless />
    </div>
    
    <x-textarea wire:model="feedbackContent" label="Feedback" placeholder="Write your review here" />
    
    <div class="flex justify-end items-center">
        <x-button label="Submit Review" />
    </div>
</form>