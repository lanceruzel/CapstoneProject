<div class="flex flex-wrap justify-center items-center gap-5">
    <!-- Total Orders -->
    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-blue-100 p-3 rounded-md">
            <x-icon name="folder" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalProducts }}</p>
            <p>Total Products</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-green-100 p-3 rounded-md">
            <x-icon name="check" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalAvailable }}</p>
            <p>Available</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-violet-100 p-3 rounded-md">
            <x-icon name="document-magnifying-glass" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalForReview }}</p>
            <p>For Review</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-red-100 p-3 rounded-md">
            <x-icon name="archive-box-x-mark" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalSuspended }}</p>
            <p>Suspended</p>
        </div>
    </div>
</div>
