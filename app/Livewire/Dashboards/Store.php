<?php

namespace App\Livewire\Dashboards;

use App\Enums\Status;
use App\Livewire\Pages\Orders;
use App\Models\Order;
use App\Models\OrderedItem;
use IcehouseVentures\LaravelChartjs\Facades\Chartjs;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Store extends Component{
    public function getMonthlySales(){
        $sales = Order::selectRaw('MONTH(created_at) as month, SUM(total) as total_sales')
                  ->where('seller_id', Auth::id())
                  ->where('status', Status::OrderBuyerReceived)
                  ->whereYear('created_at', now()->year)
                  ->groupBy('month')
                  ->orderBy('month')
                  ->pluck('total_sales', 'month')
                  ->toArray();

        // Fill in missing months with zero sales
        $monthlySales = array_fill(1, 12, 0); // Initialize all months with zero
        
        foreach ($sales as $month => $total) {
            $monthlySales[$month] = $total;
        }

        return array_values($monthlySales);
    }

    public function salesChart(){
        // dd($this->getMonthlySales());
        $data = [
            'labels' => ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            'data' => $this->getMonthlySales(),
        ];

        $chart = Chartjs::build()
            ->name("SalesChart")
            ->type("line")
            ->size(['width' => 400, 'height' => 400])
            ->labels($data['labels'])
            ->datasets([
                [
                    "label" => "Sales in $",
                    "backgroundColor" => "rgba(38, 185, 154, 0.31)",
                    "borderColor" => "rgba(38, 185, 154, 0.7)",
                    "data" => $data['data'],
                ]
            ])
            ->options([
                'title' => [
                    'display'=> true,
                    'text'=> 'Monthly Sales',
                    'font'=> [
                        'size' => 14
                    ]
                ],
                'responsive' => true,
                'maintainAspectRatio' => false,
                'legend' => [
                    'display' => false
                ],
                'scales' => [
                    'y' => [
                        'ticks' => [
                            'display' => true,
                            'callback' => function ($value) {
                                return '$' . $value;
                            },
                        ]
                    ]
                ]
            ]);
            
        return $chart;
    }

    public function getSalesStatusCount(){
        $uId = Auth::id();

        return [
            'delivered' => Order::where('seller_id', $uId)->where('status', Status::OrderBuyerReceived)->count(),
            'cancelled' => Order::where('seller_id', $uId)->where('status', Status::OrderSellerCancel)->orWhere('status', Status::OrderBuyerCancel)->count()
        ];
    }

    public function salesStat(){
        $chart = Chartjs::build()
            ->name("SalesStat")
            ->type("pie")
            ->size(['width' => 400, 'height' => 200])
            ->labels(['Delivered', 'Cancelled',])
            ->datasets([
                [
                    "label" => "Stat",
                    "backgroundColor" => ['#f87171', '#4ade80'],
                    "borderColor" => '#f8fafc',
                    "data" => array_values($this->getSalesStatusCount()),
                ]
            ])
            ->options([
                'title' => [
                    'display'=> true,
                    'text'=> 'Stat',
                    'font'=> [
                        'size' => 18
                    ]
                ],
                'responsive' => true,
                'maintainAspectRatio' => false
            ]);

        return $chart;
    }
    
    public function recentOrders(){
        return Order::where('seller_id', Auth::id())->orderBy('id', 'desc')->take(6)->get();
    }

    public function topSoldProduct(){
        $sellerId = Auth::id();
        return OrderedItem::select('product_id', \DB::raw('COUNT(*) as total_count'))
                      ->whereHas('order', function ($query) use ($sellerId) {
                          $query->where('seller_id', $sellerId);
                      })
                      ->groupBy('product_id')
                      ->orderBy('total_count', 'desc')
                      ->with('product')
                      ->take(6)
                      ->get();
    }

    public function render(){
        return view('livewire.Dashboards.store', [
            'recentOrders' => $this->recentOrders(),
            'topSold' => $this->topSoldProduct(),
            'chart' => $this->salesChart(),
            'chart2' => $this->salesStat()
        ]);
    }
}
