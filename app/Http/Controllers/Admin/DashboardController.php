<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Counter;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $institutionId = Auth::user()->institution_id;
        $today = Carbon::today();

        // 1. Summary Cards
        $totalToday = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->count();
            
        $servingNow = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->whereIn('status', ['calling', 'serving'])
            ->count();
            
        $completedToday = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->where('status', 'completed')
            ->count();

        // Average Service Time (in minutes)
        $avgServiceTimeSec = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->where('status', 'completed')
            ->whereNotNull('called_at')
            ->whereNotNull('completed_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(SECOND, called_at, completed_at)) as avg_time'))
            ->first()->avg_time ?? 0;
            
        $avgServiceTime = $avgServiceTimeSec > 60 
            ? floor($avgServiceTimeSec / 60) . 'm ' . ($avgServiceTimeSec % 60) . 's'
            : round($avgServiceTimeSec) . 's';

        // 2. Service Statistics
        $serviceStats = ServiceType::where('institution_id', $institutionId)
            ->withCount(['queues' => function($query) use ($today) {
                $query->whereDate('queue_date', $today);
            }])
            ->get()
            ->map(function($service) use ($totalToday) {
                $service->percentage = $totalToday > 0 ? round(($service->queues_count / $totalToday) * 100, 1) : 0;
                return $service;
            });

        // 3. Active Counters
        $counters = Counter::where('institution_id', $institutionId)
            ->with(['operator', 'serviceType'])
            ->get()
            ->map(function($counter) use ($today) {
                // Get the queue currently being served at this counter
                $counter->current_queue = Queue::where('counter_id', $counter->id)
                    ->whereDate('queue_date', $today)
                    ->whereIn('status', ['calling', 'serving'])
                    ->first();
                return $counter;
            });

        // 4. Recent Queues Table
        $recentQueues = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->with(['serviceType', 'counter'])
            ->latest()
            ->take(10)
            ->get();

        // 5. Visitor Chart (Last 7 Days)
        $chartData = Queue::where('institution_id', $institutionId)
            ->where('queue_date', '>=', Carbon::today()->subDays(6))
            ->select(DB::raw('DATE(queue_date) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        // 6. Recent Activities (Time-line)
        $recentActivities = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->whereNotNull('called_at')
            ->with(['counter', 'serviceType'])
            ->orderBy('called_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalToday', 
            'servingNow', 
            'completedToday', 
            'avgServiceTime',
            'serviceStats',
            'counters',
            'recentQueues',
            'chartData',
            'recentActivities'
        ));
    }
}
