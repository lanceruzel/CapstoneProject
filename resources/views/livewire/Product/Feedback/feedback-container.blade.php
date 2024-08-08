@php
    $rating = $feedback->rating;

    $roundedRating = round($rating * 2) / 2; // Round to the nearest 0.5
    $fullStars = floor($roundedRating); // Get the integer part of the rating
    $halfStar = $roundedRating - $fullStars == 0.5; // Check if there's a half star
@endphp

<div class="flex flex-row gap-3 py-5">
    <div class="min-w-12 min-h-12 size-12 rounded-full">
        @if($feedback->user->profilePicture() == null)
            <img src="https://static.everypixel.com/ep-pixabay/0329/8099/0858/84037/3298099085884037069-head.png" alt="" class="bg-gray-100 w-full h-full object-cover rounded-full">
        @else
            <img src="{{ asset('uploads') . '/' . $feedback->user->profilePicture() }}" class="w-full h-full object-cover rounded-full">
        @endif
    </div>

    <div class="space-y-3 w-full">
        <div class="flex justify-between">
            <div class="flex gap-2">
                <div class="flex flex-col justify-center">
                    <p>{{ $feedback->user->userInformation->fullname() }}</p>

                    <div class="pe-3">
                        @for ($i = 1; $i <= 5; $i++) 
                            @if ($i <=$fullStars) 
                                <i class="ri-star-fill text-yellow-300"></i>
                            @elseif ($halfStar && $i == $fullStars + 1)
                                <i class="ri-star-half-fill text-yellow-300"></i>
                            @else
                                <i class="ri-star-fill text-gray-500"></i>
                            @endif
                        @endfor
                    </div>

                    {{-- <p class="text-xs text-gray-500">Variation: variation2</p> --}}
                </div>
            </div>

            <div>
                <p class="text-xs text-gray-500">{{ date_format($feedback->created_at, "M d, Y") }}</p>
            </div>
        </div>

        <div>
            <span>
                {{ $feedback->content}}
            </span>
        </div>
    </div>
</div>