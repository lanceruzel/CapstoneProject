<?php

namespace App\Livewire\Appeal;

use App\Models\ReportAppeal;
use Livewire\Component;
use Livewire\WithPagination;

class ReportAppealsTable extends Component
{
    use WithPagination;

    protected $listeners = [
        'refresh-report-appeals-table' => '$refresh',
    ];

    public function render()
    {
        return view('livewire.Appeal.report-appeals-table', [
            'appeals' => ReportAppeal::orderBy('id', 'desc')->paginate(10)
        ]);
    }
}
