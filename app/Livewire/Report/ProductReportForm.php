<?php

namespace App\Livewire\Report;

use App\Classes\UserNotif;
use App\Enums\NotificationType;
use App\Enums\Status;
use App\Models\Order;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use WireUi\Traits\WireUiActions;

class ProductReportForm extends Component
{
    use WithFileUploads;
    use WireUiActions;

    public $images;
    public $description;

    public $order;
    public $orderedProducts = [];
    public $selectedProducts = [];
    public $reason;

    protected $listeners = [
        'get-order-info' => 'getData',
        'clearProductReportFormModalData' => 'clearData'
    ];

    public function getData($id){
        $this->order = Order::findOrFail($id);
        
        if($this->order){
            $this->orderedProducts = $this->order->orderedItems->map(function ($item) {
                return [
                    'id' => ['id' => $item->product->id, 'name' => $item->product->name],
                    'name' => $item->product->name,
                ];
            })
            ->unique('id')
            ->toArray();
        }
    }

    public function send(){
        $validated = $this->validateForm();

        if(!$this->isReturnOrderApplicable()){
            return;
        }

        try{
            $store = $this->storeReport($validated);

            if($store){
                $this->notification()->send([
                    'icon' => 'success',
                    'title' => 'Success!',
                    'description' => 'Your return request has been sent.',
                ]);

                UserNotif::sendNotif($this->order->seller_id, 'Your have received a return request.' , NotificationType::ReturnRequest);
                
                $this->dispatch('close-modal', ['modal' => 'productReportFormModal']);
                $this->dispatch('refresh-order-container', ['id' => $this->order->id]);
            }else{
                $this->notification()->send([
                    'icon' => 'error',
                    'title' => 'Error!',
                    'description' => 'Woops, its an error. There seems to be a problem sending your request.',
                ]);
            }
        }catch(\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error.',
            ]);

            Log::error('Error send request in product report: ' . $e->getMessage());
        }
    }

    public function isReturnOrderApplicable(){
        if($this->order->status == Status::OrderBuyerReceived){
            $orderedDate = Carbon::parse($this->order->updated_at);
            $isWithinLast24Hours = $orderedDate->greaterThanOrEqualTo(Carbon::now()->subDay());

            if($isWithinLast24Hours){
                return true;
            }else{
                $this->dispatch('close-modal', ['modal' => 'productReturnFormModal']);

                $this->dialog()->show([
                    'icon' => 'info',
                    'title' => 'Return Policy!',
                    'description' => 'Unfortunately, your return request cannot be processed because the order was placed more than 24 hours ago. Our return policy allows returns within 24 hours of receipt. Thank you for your understanding',
                ]);
            }
        }

        return false;
    }

    public function storeReport($validated){
        return Report::create([
            'reporter_id' => Auth::id(),
            'seller_id' => $this->order->seller_id,
            'order_id' => $this->order->id,
            'reason' => $validated['reason'],
            'products' => json_encode($validated['selectedProducts']),
            'description' => $validated['description'],
            'images' => json_encode($this->storeImages($validated['images'])),
            'status' => Status::ReturnRequestReview
        ]);
    }

    public function deleteImage($index){
        array_splice($this->images, $index, 1);
    }

    public function storeImages($images){
        $imagePaths = [];

        if($images){
            foreach ($images as $key => $image) {
                // New image, store and get path
                $filename = $key . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('report', $filename);
                array_push($imagePaths, $filename);
            }
        }

        return $imagePaths;
    }

    public function validateForm(){
        $rules = [
            'reason' => 'required',
            'description' => 'required|min:10',
            'images.*' => 'image|mimes:png,jpg,jpeg',
            'selectedProducts' => 'required'
        ];

        if(empty($this->images) || !$this->images || $this->images == '[]'){
            $rules['images'] = 'required|image|mimes:png,jpg,jpeg';
        }

        return $this->validate($rules);
    }

    public function clearData(){
        $this->reset([
            'images',
            'description',
            'order',
            'reason',
        ]);

        $this->selectedProducts = [];
        $this->orderedProducts = [];
    }

    public function render()
    {
        return view('livewire.Report.product-report-form');
    }
}
