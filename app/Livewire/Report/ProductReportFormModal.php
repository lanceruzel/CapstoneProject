<?php

namespace App\Livewire\Report;

use App\Models\OrderedItem;
use App\Models\Product;
use App\Models\ProductReport;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use WireUi\Traits\WireUiActions;

class ProductReportFormModal extends Component
{
    use WireUiActions;
    use WithFileUploads;

    public $images;
    public $content;

    public $product;
    public $orderedItem;

    protected $listeners = [
        'open-product-report' => 'getData',
        'clearProductReportFormModalData' => 'clearData'
    ];

    public function getData($id){
        $this->orderedItem = OrderedItem::findOrFail($id);

        if($this->orderedItem){
            $this->product = $this->orderedItem->product;
        }
    }

    public function store(){
        $validated = $this->validate([
            'images.*' => 'image|mimes:png,jpg,jpeg',
            'content' => 'required'
        ]);

        try{
            if($this->product){
                $store = $this->storeReport($validated);

                if($store){
                    $this->notification()->send([
                        'icon' => 'success',
                        'title' => 'Success!',
                        'description' => 'Report successfully submitted.',
                    ]);

                    $this->dispatch('refresh-order-container', ['id' => $this->orderedItem->order->id]);
                    $this->dispatch('close-modal', ['modal' => 'productReportFormModal']);
                }else{
                    $this->notification()->send([
                        'icon' => 'error',
                        'title' => 'Error!',
                        'description' => 'Woops, its an error. There seems to be a problem submitting your report.',
                    ]); 

                    return;
                }
            }else{
                $this->notification()->send([
                    'icon' => 'error',
                    'title' => 'Error!',
                    'description' => 'Woops, its an error. There seems to be a problem submitting your report.',
                ]);

                return;
            }

        }catch(\Exception $e){
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error!',
                'description' => 'Woops, its an error. ' . $e->getMessage(),
            ]);

            return;
        }
    }

    public function clearData(){
        $this->reset([
            'content',
            'images'
        ]);

        $this->orderedItem = null;
        $this->product = null;
    }

    public function storeReport($validated){
        return ProductReport::create([
            'reporter_id' => Auth::id(),
            'product_id' => $this->product->id,
            'content' => $validated['content'],
            'images' => json_encode($this->storeImages($this->images))
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
                $image->storeAs('reports', $filename);
                array_push($imagePaths, $filename);
            }
        }

        return $imagePaths;
    }

    public function render()
    {
        return view('livewire.Report.product-report-form-modal');
    }
}
