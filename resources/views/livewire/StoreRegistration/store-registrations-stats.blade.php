<div class="flex flex-wrap justify-center items-center gap-5">
    <!-- Total Orders -->
    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-blue-100 p-3 rounded-md">
            <x-icon name="building-storefront" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalRegistrations }}</p>
            <p>Total Registrations</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-green-100 p-3 rounded-md">
            <x-icon name="document-check" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalAccepted }}</p>
            <p>Accepted</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-blue-100 p-3 rounded-md">
            <x-icon name="document-magnifying-glass" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalForReview }}</p>
            <p>For Review</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-yellow-100 p-3 rounded-md">
            <x-icon name="document-arrow-up" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalForSubmission }}</p>
            <p>For Submission</p>
        </div>
    </div>

    <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
        <div class="bg-pink-100 p-3 rounded-md">
            <x-icon name="document-arrow-up" class="w-5 h-5" />
        </div>

        <div>
            <p class="text-xl font-medium">{{ $totalForReSubmission }}</p>
            <p>For ReSubmission</p>
        </div>
    </div>
</div>
