<div class="flex flex-col gap-7">
    <!-- Headers -->
    <div class="flex flex-wrap justify-center items-center gap-5">

        <!-- Total Orders -->
        <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
            <div class="bg-blue-100 p-3 rounded-md">
                <x-icon name="truck" class="w-5 h-5" />
            </div>

            <div>
                <p class="text-xl font-medium">23</p>
                <p>Total Users</p>
            </div>
        </div>

        <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
            <div class="bg-violet-100 p-3 rounded-md">
                <x-icon name="device-phone-mobile" class="w-5 h-5" />
            </div>

            <div>
                <p class="text-xl font-medium">23</p>
                <p>Total Product</p>
            </div>
        </div>

        <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
            <div class="bg-green-100 p-3 rounded-md">
                <x-icon name="banknotes" class="w-5 h-5" />
            </div>

            <div>
                <p class="text-xl font-medium">$32</p>
                <p>Total Sales</p>
            </div>
        </div>

        <div class="bg-white flex flex-grow items-center p-7 gap-3 rounded-md shadow">
            <div class="bg-pink-100 p-3 rounded-md">
                <x-icon name="user-group" class="w-5 h-5" />
            </div>

            <div>
                <p class="text-xl font-medium">32</p>
                <p>Total Affiliates</p>
            </div>
        </div>

    </div>

    <!-- Charts -->
    <div class="grid grid-cols-12 gap-7 lg:max-h-[400px]">
        <div class="col-span-12 lg:col-span-8 bg-white rounded-md shadow flex items-center justify-center">
            <div class="flex items-center justify-center min-w-[90%] min-h-[90%]">
                {{-- <x-chartjs-component :chart="$chart" /> --}}
            </div>
        </div>

        <div class="col-span-12 lg:col-span-4 bg-white rounded-md shadow flex items-center justify-center">
            <div class="flex items-center justify-center min-w-[90%] min-h-[90%]">
                {{-- <x-chartjs-component :chart="$chart2" /> --}}
            </div>
        </div>
    </div>

    <!-- Other Stats -->
    <div class="grid grid-cols-12 lg:h-[400px] gap-7">

        <!-- Orders -->
        <div class="col-span-12 lg:col-span-8 p-5 bg-white rounded-md shadow">
            <p class="font-bold text-lg">Recent Orders Orders</p>

            <table class="table-fixed w-full mt-3">
                <thead>
                    <tr>
                        <th class="font-medium">Orders ID</th>
                        <th class="font-medium w-[250px]">Customer Name</th>
                        <th class="font-medium">Date</th>
                        <th class="font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    {{-- @foreach ($recentOrders as $order)
                        <tr>
                            <td class="py-2 text-center">#{{ $order->id }}</td>
                            <td class="py-2 text-center w-20">{{ $order->user->name() }}</td>
                            <td class="py-2 text-center">{{ date_format($order->created_at, "M d, Y") }}</td>
                            <td class="py-2 text-center">
                                @if($order->status == App\Enums\Status::OrderSellerConfirmation)
                                    <x-badge flat warning label="Pending" />
                                @elseif($order->status == App\Enums\Status::OrderSellerPreparing)
                                    <x-badge flat label="Processing" />
                                @elseif($order->status == App\Enums\Status::OrderSellerShipped)
                                    <x-badge flat label="Processing" />
                                @elseif($order->status == App\Enums\Status::OrderSellerCancel)
                                    <x-badge flat negative label="Processing" />
                                @elseif($order->status == App\Enums\Status::OrderBuyerReceived)
                                    <x-badge flat positive label="Completed" />
                                @elseif($order->status == App\Enums\Status::OrderBuyerCancel)
                                    <x-badge flat negative label="Cancelled" />
                                @else
                                    <span>{{ $order->status }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach --}}
                </tbody>
            </table>
        </div>

        <!-- Top Selling -->
        <div class="col-span-12 lg:col-span-4 p-5 bg-white rounded-md shadow">
            <p class="font-bold text-lg">Top Sold Products</p>

            <div class="overflow-auto">
                {{-- @foreach ($topSold as $product)
                    <div class="flex justify-between gap-3 py-3">
                        <div class="flex items-center gap-3">
                            <div class="rounded-lg w-12 h-12 border">
                                <img src="{{ asset('uploads/products') . '/' . json_decode($product->product->images)[0] }}" class="w-full h-full object-cover object-center rounded-lg" alt="...">
                            </div>

                            <p>{{ $product->product->name }}</p>
                        </div>
    
                        <div class="leading-none text-center">
                            <p>{{ $product->total_count }}</p>
                            <small>Sold</small>
                        </div>
                    </div>
                @endforeach --}}
            </div>
        </div>
    </div>
</div>