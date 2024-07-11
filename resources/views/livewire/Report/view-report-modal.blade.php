<x-modal-card name="productReportModal" title="View Report" align='center' x-cloak x-on:close="$dispatch('productReportModalData')" blurless wire:ignore.self>  
    @if($report) 
        <div class="flex flex-col gap-2 items-start text-gray-600 overflow-auto">
            @if($report->product->status == App\Enums\Status::Suspended)
                <x-alert title="This product is already suspended." info />
            @endif

            <div class="w-full">
            <p class="font-semibold">Product: <span class="font-normal">{{ $report->product->name }}</span></p>
            <p class="font-semibold">Seller: <span class="font-normal">{{ $report->product->seller->storeInformation->name }}</span></p>
            <p class="font-semibold">Reporter: <span class="font-normal">{{ $report->user->userInformation->fullname() }}</span></p>
            </div>

            <div class="w-full">
            <p class="font-semibold">Report Content:</p>
            <p class="indent-5">{{ $report->content }}</p>
            </div>

            <div class="w-full" wire:ignore>
                @if($images != null)
                    @if(count($images) === 1)
                        <!-- post image -->
                        <div class="relative w-full h-full" uk-lightbox>
                            <a href="{{ asset('uploads/reports') . '/' . $images[0] }}">
                                <img src="{{ asset('uploads/reports') . '/' . $images[0] }}" alt="" class="sm:rounded-lg w-full h-full object-cover">
                            </a>
                        </div>
                    @elseif(count($images) > 1)
                        <!-- slide images -->
                        <div class="relative uk-visible-toggle uk-slideshow w-full" tabindex="-1" uk-slideshow="animation: push;finite: true;min-height: 300; max-height: 350">

                            <ul class="uk-slideshow-items" uk-lightbox="" style="min-height: 350px;">
                                @foreach($images as $image)
                                    <li class="w-full sm:rounded-md" tabindex="-1" style="">
                                        <a href="{{ asset('uploads/reports') . '/' . $image }}">
                                            <img src="{{ asset('uploads/reports') . '/' . $image }}" class="w-full h-full object-cover inset-0" alt="">
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- navigation -->
                            <button type="button" class="absolute -left-3 -translate-y-1/2 bg-gray-100/50 backdrop-blur-xl rounded-full top-1/2 grid w-8 h-7 place-items-center border" uk-slideshow-item="previous">
                                <x-icon name="chevron-left" class="w-5 h-5" />
                            </button>

                            <button type="button" class="absolute -right-3 -translate-y-1/2 bg-gray-100/50 backdrop-blur-xl rounded-full top-1/2 grid w-8 h-7 place-items-center border uk-invisible" uk-slideshow-item="next">
                                <x-icon name="chevron-right" class="w-5 h-5" />
                            </button>
                        </div>
                    @endif
                @endif
            </div>

            <x-slot name="footer" class="flex justify-end gap-x-4">
            <x-button flat label="Close" x-on:click="close" />

            @if($report->product->status != App\Enums\Status::Suspended)
                <x-button outline negative wire:loading.attr="disabled" wire:click="suspensionConfirmation" spinner="declineOrder" label="Suspend Product" />
            @endif
            </x-slot>
        </div>
    @else
        <div class="flex items-center justify-center w-full">
            <div class="flex items-row items-center justify-center gap-3">
                <x-icon name='arrow-path' class="h-5 w-5 animate-spin"/>

                <span>
                Fetching data...
                </span>
            </div>
        </div>
    @endif
 </x-modal-card>