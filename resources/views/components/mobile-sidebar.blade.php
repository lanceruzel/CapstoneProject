@php
    use App\Classes\StoreRegistration;

    $storeRegistration = new StoreRegistration();
@endphp

<aside x-bind:class="sidebarOpened ? 'translate-x-0' : '-translate-x-full'" x-transition class="md:hidden transition-all fixed top-auto h-full w-64 left-0 border-e pt-16 bg-white z-[5]">
    <div class="py-3 px-2.5 flex items-center justify-between flex-col h-full">
        <ul class="space-y-2 w-full">
            <li>
                @if(request()->routeIS('home'))
                    <a href="{{ route('home') }}" class='transition-all flex items-center gap-3 font-bold bg-gray-100 border-gray-100 px-7 py-2 w-full hover:text-gray-700 hover:font-bold hover:bg-gray-100'> 
                        <x-icon name="home" class="w-5 h-5" solid />
                        
                        <span class="text-lg">
                            Home
                        </span>
                    </a>
                @else
                    <a href="{{ route('home') }}" class='transition-all flex items-center gap-3 font-medium px-7 py-2 w-full hover:text-gray-700 hover:font-bold hover:bg-gray-100'> 
                        <x-icon name="home" class="w-5 h-5" />
                        
                        <span class="text-lg">
                            Home
                        </span>
                    </a>
                @endif
            </li>

            <li>
                <a href="#" class='transition-all flex items-center gap-3 font-medium px-7 py-2 w-full hover:text-gray-700 hover:font-bold hover:bg-gray-100' uk-toggle="target: #notification-slide"> 
                    <x-icon name="bell" class="w-5 h-5" />
                    
                    <span class="text-lg">
                        Notification
                    </span>
                </a>
            </li>
            
            <li>
                @if(request()->routeIS('market'))
                    <a href="{{ route('market') }}" class='transition-all flex items-center gap-3 font-bold bg-gray-100 px-7 py-2 w-full hover:text-gray-700 hover:font-bold hover:bg-gray-100'> 
                        <x-icon name="shopping-bag" class="w-5 h-5" solid />
                        
                        <span class="text-lg">
                            Market
                        </span>
                    </a>
                @else
                    <a href="{{ route('market') }}" class='transition-all flex items-center gap-3 font-medium px-7 py-2 w-full hover:text-gray-700 hover:font-bold hover:bg-gray-100'> 
                        <x-icon name="shopping-bag" class="w-5 h-5" />
                        
                        <span class="text-lg">
                            Market
                        </span>
                    </a>
                @endif
            </li>

            @if(auth()->user()->role != App\Enums\UserType::Store)
                <li>
                    @if(request()->routeIS('cart'))
                        <a href="{{ route('cart') }}" class='transition-all flex items-center gap-3 font-bold bg-gray-100 px-7 py-2 w-full hover:text-gray-700 hover:font-bold hover:bg-gray-100'> 
                            <x-icon name="shopping-cart" class="w-5 h-5" solid />
                            
                            <span class="text-lg">
                                My Cart
                            </span>
                        </a>
                    @else
                        <a href="{{ route('cart') }}" class='transition-all flex items-center gap-3 font-medium px-7 py-2 w-full hover:text-gray-700 hover:font-bold hover:bg-gray-100'> 
                            <x-icon name="shopping-cart" class="w-5 h-5" />
                            
                            <span class="text-lg">
                                My Cart
                            </span>
                        </a>
                    @endif
                </li>
            @endif

            <li>
                @if(request()->routeIS('message'))
                    <a href="{{ route('message') }}" class='transition-all flex items-center gap-3 font-bold bg-gray-100 px-7 py-2 w-full hover:text-gray-700 hover:font-bold hover:bg-gray-100'> 
                        <x-icon name="chat-bubble-bottom-center-text" class="w-5 h-5" solid />
                        
                        <span class="text-lg">
                            Messages
                        </span>
                    </a>
                @else
                    <a href="{{ route('message') }}" class='transition-all flex items-center gap-3 font-medium px-7 py-2 w-full hover:text-gray-700 hover:font-bold hover:bg-gray-100'> 
                        <x-icon name="chat-bubble-bottom-center-text" class="w-5 h-5" />
                        
                        <span class="text-lg">
                            Messages
                        </span>
                    </a>
                @endif
            </li>

            <li>
                @if(request()->routeIS('profile'))
                    <a href="{{ route('profile') }}" class='transition-all flex items-center gap-3 font-bold bg-gray-100 px-7 py-2 w-full hover:text-gray-700 hover:font-bold hover:bg-gray-100'> 
                        <x-icon name="user-circle" class="w-5 h-5" solid />
                        
                        <span class="text-lg">
                            Profile
                        </span>
                    </a>
                @else
                    <a href="{{ route('profile') }}" class='transition-all flex items-center gap-3 font-medium px-7 py-2 w-full hover:text-gray-700 hover:font-bold hover:bg-gray-100'> 
                        <x-icon name="user-circle" class="w-5 h-5" />
                        
                        <span class="text-lg">
                            Profile
                        </span>
                    </a>
                @endif
            </li>
        </ul>

        <div class="border-t-2 w-full pt-2">
            <x-dropdown position="top">
                <x-slot name="trigger">
                    <div class='transition-all flex items-center justify-start gap-2 font-medium px-7 py-2 w-full hover:text-gray-700 hover:font-bold hover:bg-gray-100'> 
                        <x-icon name="cog-6-tooth" class="w-5 h-5" solid />
                        
                        <span class="text-sm font-medium text-center line-clamp-1">
                            {{ auth()->user()->name() }}
                        </span>
                    </div>
                </x-slot>

                <x-dropdown.item icon="arrow-path" onclick="$openModal('changeCurrencyModal')">
                    <p>Currency: {{ auth()->user()->currency }}</p> 
                </x-dropdown.item>

                @if(auth()->user()->role == App\Enums\UserType::Store || auth()->user()->role == App\Enums\UserType::Travelpreneur)
                    @if($storeRegistration->isRegistered())
                        <x-dropdown.item href="{{ route('store.dashboard') }}" icon='building-storefront' label="Store Management" />
                    @else
                        <x-dropdown.item icon='building-storefront' label="Register Store" onclick="$openModal('storeRegistrationFormModal')" />
                    @endif
                @else
                    <x-dropdown.item icon='user-group' label="Affiliates" onclick="$openModal('affiliateDashboardModal')" />
                @endif
    
                <x-dropdown.item separator icon='arrow-left-end-on-rectangle' href="{{ route('signout') }}" label="Sign out" />
            </x-dropdown>
        </div>
    </div>
</aside>