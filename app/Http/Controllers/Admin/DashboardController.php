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

        // 1. Summary Cards / Main Metrics
        $totalToday = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->count();
            
        $servingNow = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->whereIn('status', ['calling', 'serving'])
            ->count();
            
        $waitingToday = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->where('status', 'waiting')
            ->count();

        $completedToday = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->where('status', 'completed')
            ->count();

        $skippedToday = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->where('status', 'skipped')
            ->count();

        // Weekly & Monthly counts
        $totalWeek = Queue::where('institution_id', $institutionId)
            ->where('queue_date', '>=', Carbon::now()->startOfWeek())
            ->count();

        $totalMonth = Queue::where('institution_id', $institutionId)
            ->whereMonth('queue_date', Carbon::now()->month)
            ->whereYear('queue_date', Carbon::now()->year)
            ->count();

        // Target / standard capacity estimations
        $targetDaily = max(50, $totalToday > 0 ? (int)ceil($totalToday * 1.25) : 50);
        $targetWeekly = max(250, $totalWeek > 0 ? (int)ceil($totalWeek * 1.2) : 250);
        $targetMonthly = max(1000, $totalMonth > 0 ? (int)ceil($totalMonth * 1.15) : 1000);

        // Average Service Time (in minutes / seconds)
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

        // 3. Active Counters (Only show counters that have an assigned operator/user)
        $counters = Counter::where('institution_id', $institutionId)
            ->whereNotNull('user_id')
            ->with(['operator', 'serviceType'])
            ->get()
            ->map(function($counter) use ($today) {
                $counter->current_queue = Queue::where('counter_id', $counter->id)
                    ->whereDate('queue_date', $today)
                    ->whereIn('status', ['calling', 'serving'])
                    ->first();
                $counter->served_count = Queue::where('counter_id', $counter->id)
                    ->whereDate('queue_date', $today)
                    ->where('status', 'completed')
                    ->count();
                return $counter;
            });

        // 4. Recent Queues Table / Tasks
        $recentQueues = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->with(['serviceType', 'counter.operator'])
            ->latest()
            ->take(10)
            ->get();

        // 5. Visitor Chart (Monthly/Daily trend)
        $chartData = Queue::where('institution_id', $institutionId)
            ->where('queue_date', '>=', Carbon::today()->subDays(6))
            ->select(DB::raw('DATE(queue_date) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        // 6. Recent Activities (Time-line / Call Log)
        $recentActivities = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->whereNotNull('called_at')
            ->with(['counter', 'serviceType'])
            ->orderBy('called_at', 'desc')
            ->take(6)
            ->get();

        // 7. Team Members / Operators list
        $teamMembers = \App\Models\User::where('institution_id', $institutionId)
            ->with('counter')
            ->take(6)
            ->get();

        // Calculation of completion rate
        $completionRate = $totalToday > 0 ? round(($completedToday / $totalToday) * 100) : 100;

        return view('admin.dashboard', compact(
            'totalToday', 
            'servingNow', 
            'waitingToday',
            'completedToday',
            'skippedToday',
            'totalWeek',
            'totalMonth',
            'targetDaily',
            'targetWeekly',
            'targetMonthly',
            'avgServiceTime',
            'avgServiceTimeSec',
            'serviceStats',
            'counters',
            'recentQueues',
            'chartData',
            'recentActivities',
            'teamMembers',
            'completionRate'
        ));
    }

    /**
     * Return dashboard data as JSON for AJAX realtime updates.
     */
    public function apiData()
    {
        $institutionId = Auth::user()->institution_id;
        $today = Carbon::today();

        $totalToday = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)->count();
        $servingNow = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->whereIn('status', ['calling', 'serving'])->count();
        $waitingToday = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->where('status', 'waiting')->count();
        $completedToday = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->where('status', 'completed')->count();
        $skippedToday = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->where('status', 'skipped')->count();
        $totalWeek = Queue::where('institution_id', $institutionId)
            ->where('queue_date', '>=', Carbon::now()->startOfWeek())->count();
        $totalMonth = Queue::where('institution_id', $institutionId)
            ->whereMonth('queue_date', Carbon::now()->month)
            ->whereYear('queue_date', Carbon::now()->year)->count();

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

        $completionRate = $totalToday > 0 ? round(($completedToday / $totalToday) * 100) : 100;

        // Counters with current queue info
        $counters = Counter::where('institution_id', $institutionId)
            ->whereNotNull('user_id')
            ->with(['operator', 'serviceType'])
            ->get()
            ->map(function($counter) use ($today) {
                $cq = Queue::where('counter_id', $counter->id)
                    ->whereDate('queue_date', $today)
                    ->whereIn('status', ['calling', 'serving'])->first();
                $counter->current_queue_number = $cq ? $cq->queue_number : null;
                $counter->served_count = Queue::where('counter_id', $counter->id)
                    ->whereDate('queue_date', $today)
                    ->where('status', 'completed')->count();
                return $counter;
            });

        // Recent queues
        $recentQueues = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->with(['serviceType'])
            ->latest()->take(5)->get()
            ->map(fn($q) => [
                'queue_number' => $q->queue_number,
                'service_name' => $q->serviceType->name ?? '-',
                'customer_name' => $q->customer_name ?? 'Pelanggan',
                'status' => $q->status,
            ]);

        // Recent activities
        $recentActivities = Queue::where('institution_id', $institutionId)
            ->whereDate('queue_date', $today)
            ->whereNotNull('called_at')
            ->with(['counter'])
            ->orderBy('called_at', 'desc')->take(4)->get()
            ->map(fn($act) => [
                'counter_name' => $act->counter->name ?? 'Loket',
                'counter_initials' => substr($act->counter->name ?? 'L', 0, 2),
                'queue_number' => $act->queue_number,
                'called_at' => $act->called_at ? $act->called_at->format('h:i A') : '09:00 AM',
            ]);

        return response()->json([
            'totalToday' => $totalToday,
            'servingNow' => $servingNow,
            'waitingToday' => $waitingToday,
            'completedToday' => $completedToday,
            'skippedToday' => $skippedToday,
            'totalWeek' => $totalWeek,
            'totalMonth' => $totalMonth,
            'avgServiceTime' => $avgServiceTime,
            'completionRate' => $completionRate,
            'counters' => $counters,
            'recentQueues' => $recentQueues,
            'recentActivities' => $recentActivities,
        ]);
    }
}
