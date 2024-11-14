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
            'totalFulfilled' => $reportCounts[Status::ReturnRequestSellerOrderCreated] ?? 0,
            'totalForReview' => $reportCounts[Status::ReturnRequestReview] ?? 0,
            'totalDecline' => $reportCounts[Status::Declined] ?? 0,
            'totalAdminAction' => $reportCounts[Status::AdminProductSuspend] ?? 0
        ]);
    }
}
