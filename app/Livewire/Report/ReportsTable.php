<?php

namespace App\Livewire\Report;

use App\Models\ProductReport;
use Livewire\Component;
use Livewire\WithPagination;

class ReportsTable extends Component
{
    use WithPagination;

    public function getReports(){
        return ProductReport::orderBy('id', 'desc')->paginate(10);
    }

    public function render()
    {
        return view('livewire.Report.reports-table', [
            'reports' => $this->getReports()
        ]);
    }
}
