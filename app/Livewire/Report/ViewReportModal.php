<?php

namespace App\Livewire\Report;

use App\Enums\Status;
use App\Models\Product;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Str;
use WireUi\Traits\WireUiActions;

class ViewReportModal extends Component
{
    use WireUiActions;

    public $report;

    public $media;
    public $description;
    public $products;
    public $type;


    protected $listeners = [
        'viewReport' => 'getData',
        'clearViewReportModalData' => 'clearData'
    ];

    public function getData($id){
        $this->report = Report::findOrFail($id);

        if($this->report){
            $this->description = $this->report->description;
            $this->media = json_decode($this->report->media);
            $this->type = $this->report->type;
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

    public function exportReport(){
        $pdf = Pdf::loadView('pdf.productReport', ['report' => $this->report]);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'report.pdf');
    }

    public function confirmSuspend(): void{
        $this->notification()->confirm([
            'title' => 'Are you Sure?',
            'description' => 'Suspend this products?',
            'acceptLabel' => 'Yes, suspend it',
            'method' => 'suspendProducts',
        ]);
    }

    public function suspendProducts(){
        foreach(json_decode($this->report->products) as $item){
            $product = Product::findOrFail($item->id);

            if($product){
                $product->status = Status::Suspended;
                $product->save();
            }
        }

        $this->report->status = Status::AdminProductSuspend;
        $this->report->save();

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Success!',
            'description' => 'Successfully Suspended.',
        ]);

        $this->dispatch('close-modal', ['modal' => 'viewReportModal']);
        $this->dispatch('refresh-reports-table');
    }

    public function clearData(){
        $this->reset([
            'report',
            'media',
            'description',
            'products',
            'type'
        ]);
    }

    public function render(){
        return view('livewire.Report.view-report-modal');
    }
}
