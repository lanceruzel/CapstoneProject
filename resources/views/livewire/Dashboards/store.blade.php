<div class="flex flex-col gap-7">
    <!-- Headers -->
    <div class="flex flex-wrap justify-center items-center gap-5">
            @for ($i = 0; $i < 4; $i++) <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
                <div class="bg-orange-200 p-3 rounded-md">
                    <x-icon name="home" class="w-5 h-5" />
                </div>

                <div>
                    <p class="text-xl font-medium">200+</p>
                    <p>Total Customer</p>
                </div>
        </div>
        @endfor
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-12 h-[400px] gap-7">
        <div class="col-span-8 bg-white rounded-md shadow flex items-center justify-center p-5 relative">
            <canvas id="lineChart"></canvas>
        </div>

        <div class="col-span-4 bg-white rounded-md shadow flex items-center justify-center p-5">
            <canvas id="lineChart"></canvas>
        </div>
    </div>

    <!-- Other Stats -->
    <div class="grid grid-cols-12 h-[400px] gap-7">

        <!-- Orders -->
        <div class="col-span-8 p-5 bg-white rounded-md shadow">
            <p class="font-bold text-lg">All Orders</p>

            <table class="table-fixed w-full mt-3">
                <thead>
                    <tr>
                        <th class="font-medium">Product</th>
                        <th class="font-medium">Orders ID</th>
                        <th class="font-medium w-[250px]">Customer Name</th>
                        <th class="font-medium">Date</th>
                        <th class="font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @for ($i = 0; $i < 6; $i++)
                        <tr>
                            <td class="py-1 flex items-center justify-center">
                                <div class="bg-red-500 size-10 rounded"></div>
                            </td>
                            <td class="py-1 text-center">#343</td>
                            <td class="py-1 text-center w-20">Lance Ruzel C. Ambrocio</td>
                            <td class="py-1 text-center">Jan 25, 2024</td>
                            <td class="py-1 text-center">Pending</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>

        <!-- Top Selling -->
        <div class="col-span-4 p-5 bg-white rounded-md shadow">
            <p class="font-bold text-lg">Top Sold Products</p>

            <div class="overflow-auto">
                @for ($i = 0; $i < 5; $i++)
                    <div class="flex justify-between gap-3 py-2">
                        <div class="flex items-center gap-3">
                            <div class="bg-red-500 size-10 rounded"></div>
                            <p class="text-sm">Product Name Tesitng</p>
                        </div>
    
                        <div class="leading-none">
                            <p>109</p>
                            <small>Sold</small>
                        </div>
                    </div>
                @endfor
            </div>

        </div>
    </div>

    @push('body-js')
        <script>

        </script>
    @endpush
</div>