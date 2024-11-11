<?php

namespace App\Livewire\Report;

use App\Enums\Status;
use App\Models\Report;
use Livewire\Component;

class ReportStats extends Component
{
    public function render() {
        $totalReports = Report::count();
        $reportCounts = Report::selectRaw('status, COUNT(*) as count')
            ->whereIn('status', [Status::ReturnRequestReview, Status::ReturnRequestReceieved, Status::ReturnRequestSellerOrderCreated, Status::AdminProductSuspend, Status::Declined])
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('livewire.Report.report-stats', [
            'totalReports' => $totalReports,
            'totalFulfilled' => $reportCounts[Status::ReturnRequestSellerOrderCreated],
            'totalForReview' => $reportCounts[Status::ReturnRequestReview],
            'totalDecline' => $reportCounts[Status::Declined],
            'totalAdminAction' => $reportCounts[Status::AdminProductSuspend]
        ]);
    }
}
