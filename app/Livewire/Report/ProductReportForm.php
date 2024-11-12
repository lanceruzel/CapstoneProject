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
use Str;
use WireUi\Traits\WireUiActions;

class ProductReportForm extends Component
{
    use WithFileUploads;
    use WireUiActions;

    public $media;
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

    public function identifyFileType($fileName){
        // Trim any leading/trailing spaces
        $fileName = trim($fileName);

        // Find the position of the last dot
        $dotPosition = strrpos($fileName, '.');

        // If there is no dot, it's not a file with an extension
        if ($dotPosition === false) {
            return 'unknown';
        }

        // Find the position of the first question mark (if any) after the dot
        $questionMarkPosition = strpos($fileName, '?', $dotPosition);

        // If there is no question mark, the extension ends at the end of the string
        if ($questionMarkPosition === false) {
            $extension = substr($fileName, $dotPosition + 1);
        } else {
            // If there's a question mark, extract the part before it
            $extension = substr($fileName, $dotPosition + 1, $questionMarkPosition - $dotPosition - 1);
        }

        // Convert to lowercase
        $extension = Str::lower($extension);

        // List of common video extensions
        $videoExtensions = ['mp4', 'webm', 'avi', 'mov', 'mkv', 'flv'];
        // List of common image extensions
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg'];

        // Check if the file extension matches any known video or image types
        if (in_array($extension, $videoExtensions)) {
            return 'video';
        } elseif (in_array($extension, $imageExtensions)) {
            return 'image';
        }

        return 'unknown'; // Default return if it's neither video nor image
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
            'media' => json_encode($this->storeMedia($validated['media'])),
            'status' => Status::ReturnRequestReview
        ]);
    }

    public function deleteMedia($index){
        array_splice($this->media, $index, 1);
    }

    public function storeMedia($media){
        $mediaPaths = [];

        if($media){
            foreach ($media as $key => $item) {
                // New image, store and get path
                $filename = $key . '_' . time() . '_' . uniqid() . '.' . $item->getClientOriginalExtension();
                $item->storeAs('report', $filename);
                array_push($mediaPaths, $filename);
            }
        }

        return $mediaPaths;
    }

    public function validateForm(){
        $rules = [
            'reason' => 'required',
            'description' => 'required|min:10',
            'media.*' => 'required|mimes:png,jpg,jpeg,mp4,mov,avi,wmv,mkv,webm',
            'selectedProducts' => 'required'
        ];

        if(empty($this->media) || !$this->media || $this->media == '[]'){
            $rules['media'] = 'required|mimes:png,jpg,jpeg,mp4,mov,avi,wmv,mkv,webm';
        }

        return $this->validate($rules);
    }

    public function clearData(){
        $this->reset([
            'media',
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
