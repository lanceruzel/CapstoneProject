<div class="flex flex-wrap justify-center items-center gap-5">
    <!-- Total Orders -->
    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-blue-100 p-3 rounded-md">
            <x-icon name="user-group" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalUsers }}</p>
            <p>Total Users</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-violet-100 p-3 rounded-md">
            <x-icon name="cursor-arrow-rays" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalContentCreator }}</p>
            <p>Total ContentCreators</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-green-100 p-3 rounded-md">
            <x-icon name="briefcase" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalTravelpreneurs }}</p>
            <p>Total Travelpreneurs</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-pink-100 p-3 rounded-md">
            <x-icon name="building-storefront" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalStores }}</p>
            <p>Total Stores</p>
        </div>
    </div>

</div>
