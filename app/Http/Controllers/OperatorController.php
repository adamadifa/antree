<?php

namespace App\Http\Controllers;

use App\Models\Counter;
use App\Models\Queue;
use App\Models\ServiceType;
use App\Services\QueueService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperatorController extends Controller
{
    protected $queueService;

    public function __construct(QueueService $queueService)
    {
        $this->queueService = $queueService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Find counter assigned to this user
        $counter = Counter::where('user_id', $user->id)->first();

        if (!$counter) {
            return view('operator.no_counter');
        }

        // Get current active queue for this counter
        $activeQueue = Queue::where('counter_id', $counter->id)
            ->today()
            ->active()
            ->first();

        // Get waiting list for this service type
        $waitingQueues = Queue::where('service_type_id', $counter->service_type_id)
            ->today()
            ->waiting()
            ->oldest()
            ->take(10)
            ->get();

        // Stats
        $stats = [
            'total_served' => Queue::where('counter_id', $counter->id)->today()->where('status', 'completed')->count(),
            'total_skipped' => Queue::where('counter_id', $counter->id)->today()->where('status', 'skipped')->count(),
            'waiting_count' => Queue::where('service_type_id', $counter->service_type_id)->today()->waiting()->count(),
        ];

        // Service types for transfer
        $serviceTypes = ServiceType::where('institution_id', $user->institution_id)
            ->where('id', '!=', $counter->service_type_id)
            ->get();

        return view('operator.index', compact('counter', 'activeQueue', 'waitingQueues', 'stats', 'serviceTypes'));
    }

    public function callNext()
    {
        $user = Auth::user();
        $counter = Counter::where('user_id', $user->id)->firstOrFail();

        $queue = $this->queueService->callNext($counter);

        if (!$queue) {
            return back()->with('error', 'No more tickets in wait list.');
        }

        return back()->with('success', 'Calling queue ' . $queue->queue_number);
    }

    public function recall(Queue $queue)
    {
        $this->queueService->recall($queue);
        return back()->with('success', 'Recalling queue ' . $queue->queue_number);
    }

    public function complete(Queue $queue)
    {
        $this->queueService->complete($queue);
        return back()->with('success', 'Service completed for ' . $queue->queue_number);
    }

    public function skip(Queue $queue)
    {
        $this->queueService->skip($queue);
        return back()->with('success', 'Queue ' . $queue->queue_number . ' skipped.');
    }

    public function transfer(Request $request, Queue $queue)
    {
        $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
        ]);

        $serviceType = ServiceType::findOrFail($request->service_type_id);
        
        $this->queueService->transfer($queue, $serviceType);

        return redirect()->route('operator.index')->with('success', 'Queue transferred to ' . $serviceType->name);
    }
}
