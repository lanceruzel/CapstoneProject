<?php

namespace App\Livewire\Report;

use App\Models\ProductReport;
use Livewire\Component;
use Livewire\WithPagination;

class ReportsTable extends Component
{
    use WithPagination;

    public $search = '';

    public function getReports(){
        return ProductReport::whereHas('user', function($query){
            $query->whereHas('userinformation', function($query){
                $query->where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%');
            });
        })
        ->orWhereHas('product', function($query){
            $query->where('name', 'like', '%' . $this->search . '%');
        })
        ->orderBy('id', 'desc')->paginate(10);
    }

    public function render()
    {
        return view('livewire.Report.reports-table', [
            'reports' => $this->getReports()
        ]);
    }
}
