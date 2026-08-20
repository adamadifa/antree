<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Counter;
use App\Models\Queue;
use App\Models\ServiceType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $institutionId = Auth::user()->institution_id;
        $startDate = $request->get('start_date', Carbon::today()->toDateString());
        $endDate = $request->get('end_date', Carbon::today()->toDateString());
        $serviceTypeId = $request->get('service_type_id');
        $counterId = $request->get('counter_id');
        $status = $request->get('status');

        $query = Queue::where('institution_id', $institutionId)
            ->whereBetween('queue_date', [$startDate, $endDate]);

        if ($serviceTypeId) {
            $query->where('service_type_id', $serviceTypeId);
        }

        if ($counterId) {
            $query->where('counter_id', $counterId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        // Metrics for the filtered results
        $metricsQuery = clone $query;
        $totalQueues = $metricsQuery->count();
        
        $avgWaitTime = (clone $query)->whereNotNull('called_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(SECOND, created_at, called_at)) as avg_wait'))
            ->first()->avg_wait ?? 0;

        $avgServiceTime = (clone $query)->whereNotNull('called_at')
            ->whereNotNull('completed_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(SECOND, called_at, completed_at)) as avg_service'))
            ->first()->avg_service ?? 0;

        $queues = $query->with(['serviceType', 'counter.operator'])
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $serviceTypes = ServiceType::where('institution_id', $institutionId)->get();
        $counters = Counter::where('institution_id', $institutionId)->get();

        return view('admin.reports.index', compact(
            'queues', 'serviceTypes', 'counters', 'totalQueues', 
            'avgWaitTime', 'avgServiceTime', 'startDate', 'endDate'
        ));
    }

    public function export(Request $request)
    {
        $institutionId = Auth::user()->institution_id;
        $startDate = $request->get('start_date', Carbon::today()->toDateString());
        $endDate = $request->get('end_date', Carbon::today()->toDateString());
        
        $query = Queue::where('institution_id', $institutionId)
            ->whereBetween('queue_date', [$startDate, $endDate]);

        if ($request->service_type_id) $query->where('service_type_id', $request->service_type_id);
        if ($request->counter_id) $query->where('counter_id', $request->counter_id);
        if ($request->status) $query->where('status', $request->status);

        $queues = $query->with(['serviceType', 'counter'])->get();

        $filename = "antree_report_{$startDate}_to_{$endDate}.csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Tanggal', 'Nomor', 'Layanan', 'Loket', 'Status', 'Waktu Tunggu (Detik)', 'Waktu Layanan (Detik)'];

        $callback = function() use ($queues, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($queues as $q) {
                $wait = $q->called_at ? $q->created_at->diffInSeconds($q->called_at) : '-';
                $service = ($q->called_at && $q->completed_at) ? $q->called_at->diffInSeconds($q->completed_at) : '-';
                
                fputcsv($file, [
                    $q->queue_date,
                    $q->queue_number,
                    $q->serviceType->name,
                    $q->counter->name ?? '-',
                    ucfirst($q->status),
                    $wait,
                    $service
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
