<?php

namespace App\Http\Controllers;

use App\Models\ServiceType;
use App\Models\Institution;
use App\Services\QueueService;
use Illuminate\Http\Request;

class KioskController extends Controller
{
    protected $queueService;

    public function __construct(QueueService $queueService)
    {
        $this->queueService = $queueService;
    }

    /**
     * Display the kiosk landing page for a specific institution.
     */
    public function index()
    {
        // For now, we use the first institution as default
        $institution = Institution::first();
        
        if (!$institution) {
            return "No institution found. Please seed the database.";
        }

        $services = ServiceType::where('institution_id', $institution->id)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Add wait times to each service
        foreach ($services as $service) {
            $service->wait_time = $this->queueService->getEstimatedWaitTime($service);
            $service->active_queue = $this->queueService->getCurrentQueue($service);
        }

        return view('kiosk.index', compact('institution', 'services'));
    }

    /**
     * Issue a new queue ticket.
     */
    public function takeTicket(Request $request)
    {
        $request->validate([
            'service_type_id' => 'required|exists:service_types,id',
            'customer_name' => 'nullable|string|max:100',
        ]);

        $serviceType = ServiceType::findOrFail($request->service_type_id);
        
        $queue = $this->queueService->generateNumber($serviceType, $request->customer_name);

        return response()->json([
            'success' => true,
            'message' => 'Ticket issued successfully',
            'data' => [
                'queue_number' => $queue->queue_number,
                'service_name' => $serviceType->name,
                'customer_name' => $queue->customer_name,
                'date' => $queue->queue_date->format('d M Y'),
                'time' => $queue->created_at->format('H:i'),
                'wait_time' => $this->queueService->getEstimatedWaitTime($serviceType),
            ]
        ]);
    }
}
