<?php

namespace App\Livewire\Appeal;

use App\Enums\Status;
use App\Models\ReportAppeal;
use Livewire\Component;

class AppealStats extends Component
{
    public function render(){
        $totalAppeals = ReportAppeal::count();
        $appealCounts = ReportAppeal::selectRaw('status, COUNT(*) as count')
            ->whereIn('status', [Status::Accepted, Status::Ongoing, Status::Resolved])
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('livewire.Appeal.appeal-stats', [
            'totalAppeals' => $totalAppeals,
            'totalOnGoing' => $appealCounts[Status::Ongoing] ?? 0,
            'totalResolved' => $appealCounts[Status::Resolved] ?? 0,
        ]);
    }
}
