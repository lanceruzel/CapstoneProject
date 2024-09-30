<?php

namespace App\Livewire\Report;

use App\Models\Report;
use Livewire\Component;
use Livewire\WithPagination;

class ReportsTable extends Component
{
    use WithPagination;

    public $filterStatus = [];

    public $search = '';

    public function getReports(){
        $filter = $this->filterStatus;

        return Report::orderBy('id', 'desc')->paginate(10);
    }

    public function render()
    {
        return view('livewire.Report.reports-table', [
            'reports' => $this->getReports()
        ]);
    }
}
