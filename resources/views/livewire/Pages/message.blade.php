<x-layouts.main-layout wire:ignore.self>
    <div class="w-full mx-auto h-[calc(100vh-85px)] max-md:h-[calc(100vh-100px)] max-h-screen relative -mt-5 max-md:-mt-4 max-md:w-screen max-md:-ms-5 shadow-lg">

        <div class="flex bg-white w-full">

            <!-- sidebar -->
            <div class="md:w-[360px] relative border-r h-full">

                <div id="side-chat" class="top-0 left-0 max-md:fixed max-md:w-5/6 max-md:h-screen bg-white z-50 max-md:shadow dark:bg-dark2 max-md:-translate-x-full">
                    <livewire:Messaging.inbox-container />
                </div>
                
                <!-- overly -->
                <div id="side-chat" class="bg-slate-100/40 backdrop-blur w-full h-full dark:bg-slate-800/40 z-40 fixed inset-0 md:hidden max-md:-translate-x-full" uk-toggle="target: #side-chat ; cls: max-md:-translate-x-full" tabindex="0" aria-expanded="false"></div>
            </div> 

            <!-- message center -->
            <div class="flex-1 w-screen">
                <livewire:Messaging.conversation-container :selectedID="$id ?? null"/>
            </div>

            <!-- user profile right info -->
            <div class="rightt w-full h-full absolute top-0 right-0 z-10 hidden transition-transform">
                <div class="w-[360px] border-l shadow-lg h-screen bg-white absolute right-0 top-0 uk-animation-slide-right-medium delay-200 z-50 dark:bg-dark2 dark:border-slate-700">

                    <div class="w-full h-1.5 bg-gradient-to-r to-purple-500 via-red-500 from-pink-500 -mt-px"></div>
                    
                    <div class="py-10 text-center text-sm pt-20">
                        <img src="assets/images/avatars/avatar-3.jpg" class="w-24 h-24 rounded-full mx-auto mb-3" alt="">
                        <div class="mt-8">
                            <div class="md:text-xl text-base font-medium text-black dark:text-white"> Monroe Parker  </div>
                            <div class="text-gray-500 text-sm mt-1 dark:text-white/80">@Monroepark</div>
                        </div>
                        <div class="mt-5">
                             <a href="profile.html" class="inline-block rounded-full px-4 py-1.5 text-sm font-semibold bg-gray-100">View profile</a>
                        </div>
                    </div>

                    <hr class="opacity-80 dark:border-slate-700">

                    <ul class="text-base font-medium p-3">
                        <li> 
                            <div class="flex items-center gap-5 rounded-md p-3 w-full hover:bg-gray-100"> 
                                <ion-icon name="notifications-off-outline" class="text-2xl md hydrated" role="img" aria-label="notifications off outline"></ion-icon> Mute Notification     
                                <label class="switch cursor-pointer ml-auto"> <input type="checkbox" checked=""><span class="switch-button !relative"></span></label>
                            </div>
                        </li>
                        <li> <button type="button" class="flex items-center gap-5 rounded-md p-3 w-full hover:bg-gray-100"> <ion-icon name="flag-outline" class="text-2xl md hydrated" role="img" aria-label="flag outline"></ion-icon> Report     </button></li>
                        <li> <button type="button" class="flex items-center gap-5 rounded-md p-3 w-full hover:bg-gray-100"> <ion-icon name="settings-outline" class="text-2xl md hydrated" role="img" aria-label="settings outline"></ion-icon> Ignore messages   </button> </li>
                        <li> <button type="button" class="flex items-center gap-5 rounded-md p-3 w-full hover:bg-gray-100"> <ion-icon name="stop-circle-outline" class="text-2xl md hydrated" role="img" aria-label="stop circle outline"></ion-icon> Block    </button> </li>
                        <li> <button type="button" class="flex items-center gap-5 rounded-md p-3 w-full hover:bg-red-50 text-red-500"> <ion-icon name="trash-outline" class="text-2xl md hydrated" role="img" aria-label="trash outline"></ion-icon> Delete Chat   </button> </li>
                    </ul> 
                    
                    <!-- close button -->
                    <button type="button" class="absolute top-0 right-0 m-4 p-2 bg-gray-100 rounded-full" uk-toggle="target: .rightt ; cls: hidden" aria-expanded="true">
                        <ion-icon name="close" class="text-2xl flex md hydrated" role="img" aria-label="close"></ion-icon>
                    </button>

                </div>

                <!-- overly -->
                <div class="bg-slate-100/40 backdrop-blur absolute w-full h-full dark:bg-slate-800/40" uk-toggle="target: .rightt ; cls: hidden" tabindex="0" aria-expanded="true"></div>

            </div>
        </div>

    </div>
</x-layouts.main-layout>