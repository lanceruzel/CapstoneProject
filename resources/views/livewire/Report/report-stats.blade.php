<div class="flex flex-wrap justify-center items-center gap-5">
    <!-- Total Orders -->
    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-blue-100 p-3 rounded-md">
            <x-icon name="clipboard" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalReports }}</p>
            <p>Total</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-green-100 p-3 rounded-md">
            <x-icon name="check" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalFulfilled }}</p>
            <p>Fulfilled</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-blue-100 p-3 rounded-md">
            <x-icon name="document-magnifying-glass" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalForReview }}</p>
            <p>ForReview</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-red-100 p-3 rounded-md">
            <x-icon name="archive-box-x-mark" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalDecline }}</p>
            <p>Decline</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-amber-100 p-3 rounded-md">
            <x-icon name="user" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalAdminAction }}</p>
            <p>Taken Action</p>
        </div>
    </div>
</div>
