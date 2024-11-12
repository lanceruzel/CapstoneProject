<div class="flex flex-wrap justify-center items-center gap-5">
    <!-- Total Orders -->
    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-blue-100 p-3 rounded-md">
            <x-icon name="user-group" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalAppeals }}</p>
            <p>Total Appeals</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-green-100 p-3 rounded-md">
            <x-icon name="check" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalResolved }}</p>
            <p>Resolved</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-violet-100 p-3 rounded-md">
            <x-icon name="document-magnifying-glass" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalOnGoing }}</p>
            <p>On Going</p>
        </div>
    </div>
</div>
