<x-layouts.main-layout>
    <div class="h-full w-full md:-my-10 max-md:-mt-3 max-md:-mb-12 max-md:!h-fit p-3 md:p-5 max-md:pb-14">
        <h1 class="text-2xl font-semibold pb-3 max-md:pt-2">Juan Dela Cruz's livestream</h1> 
        
        <div class="grid grid-cols-12 w-full h-full gap-3">
            <div class="col-span-12 md:col-span-7">
                <div class="bg-white w-full h-screen rounded-lg flex items-center justify-center max-md:h-[500px] shadow">
                    Video here
                </div>
            </div>

            <div class="col-span-12 md:col-span-5">
                <div class="w-full h-full bg-white rounded-lg shadow">
                    <div class="p-3 shadow">
                        <p class="text-xl font-semibold">Chats</p>
                    </div>

                    <div class="p-5 text-sm font-medium space-y-5 overflow-y-auto !h-[calc(100vh-120px)]">
                        <!-- sent -->
                        <div class="flex flex-col gap-1 items-end">
                            {{-- <img src="https://i.pravatar.cc" alt="" class="w-5 h-5 rounded-full shadow"> --}}
                            <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gradient-to-tr from-sky-500 to-blue-500 text-white shadow break-words text-wrap hyphens-auto space-y-3 text-sm">
                                <span>Testing</span>
                            </div>
                                <small class="pe-2">12:34 PM</small>
                        </div> 

                        <!-- received -->
                        @for ($i = 0; $i < 10; $i++)
                            <div>
                                <div class="ms-12">
                                    <small class="font-semibold">Juan Dela Cruz</small>
                                </div>

                                <div class="flex gap-3">
                                    <img src="https://i.pravatar.cc" alt="" class="w-9 h-9 rounded-full shadow">
                                    <div class="px-4 py-2 rounded-[20px] max-w-sm bg-gray-100 break-words !text-wrap hyphens-auto space-y-3 text-sm">
                                        <span>Testingdsadas</span>
                                    </div>
                                </div>

                                <div class="!ms-14">
                                    <small>12:34 PM</small>
                                </div>
                            </div>
                        @endfor
                    </div>

                    <div class="flex items-center md:gap-4 gap-2 p-3 overflow-hidden">
                        <x-input label="" wire:model='' placeholder="Message" />
                        
                        <x-mini-button rounded flat black icon="paper-airplane" wire:click='sendMessage' />
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.main-layout>