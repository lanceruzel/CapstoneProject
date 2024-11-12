<?php

namespace App\Livewire\Report;

use App\Enums\Status;
use App\Mail\SuspendedProductsMail;
use App\Models\Product;
use App\Models\Report;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class ProductSuspensionModal extends Component
{
    use WireUiActions;

    public $report;
    public $reason;
    public $description;

    protected $listeners = [
        'get-suspension-details' => 'getData',
        'clearProductSuspensionModalFormData' => 'clearData',
        'updatedSuspensionReason' => '$refresh'
    ];

    public function getData($id){
        $this->report = Report::find($id);
    }

    public function confirmSuspend(): void{
        $validated = $this->validateForm();

        $this->notification()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Suspend this products?',
            'acceptLabel' => 'Yes, suspend it',
            'method' => 'suspendProducts',
        ]);
    }

    public function suspendProducts(){
        $validated = $this->validateForm();

        $reason = $validated['description'] ?? $validated['reason'];
        $products = [];

        foreach(json_decode($this->report->products) as $item){
            $product = Product::findOrFail($item->id);

            if($product){
                $products[] = $product->name;
                $product->status = Status::Suspended;
                $product->remarks = $reason;
                $product->save();
            }
        }

        Mail::to($this->report->seller->email)->send(new SuspendedProductsMail($this->report->seller->name(), $products, $reason));

        $this->report->status = Status::AdminProductSuspend;
        $this->report->save();

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success!',
            'description' => 'Successfully Suspended.',
        ]);

        $this->dispatch('close-modal', ['modal' => 'viewReportModal']);
        $this->dispatch('close-modal', ['modal' => 'productSuspensionModal']);
        $this->dispatch('refresh-reports-table');
    }

    public function validateForm(){
        $rules = [
            'reason' => 'required',
        ];

        if($this->reason == 'Others'){
            $rules['description'] = 'required|min:20|max:255';
        }

        return $this->validate($rules);
    }

    public function clearData(){
        $this->reset([
            'report',
            'reason',
            'description'
        ]);
    }

    public function render()
    {
        return view('livewire.Report.product-suspension-modal');
    }
}
