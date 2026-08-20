<?php

namespace App\Services;

use App\Models\Queue;
use App\Models\ServiceType;
use App\Models\DailyCounter;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class QueueService
{
    /**
     * Generate a new queue number for a given service.
     */
    public function generateNumber(ServiceType $serviceType, ?string $customerName = null): Queue
    {
        return DB::transaction(function () use ($serviceType, $customerName) {
            $today = Carbon::today()->toDateString();

            // 1. Get with lock or create daily counter
            $dailyCounter = DailyCounter::where('institution_id', $serviceType->institution_id)
                ->where('service_type_id', $serviceType->id)
                ->whereDate('date', $today)
                ->lockForUpdate()
                ->first();

            if (!$dailyCounter) {
                try {
                    $dailyCounter = DailyCounter::create([
                        'institution_id' => $serviceType->institution_id,
                        'service_type_id' => $serviceType->id,
                        'date' => $today,
                        'last_number' => 0,
                    ]);
                } catch (\Exception $e) {
                    // Fallback in case of race condition between where and create
                    $dailyCounter = DailyCounter::where('institution_id', $serviceType->institution_id)
                        ->where('service_type_id', $serviceType->id)
                        ->whereDate('date', $today)
                        ->first();
                }
            }

            // 2. Increment the number
            $newNumber = $dailyCounter->last_number + 1;
            $dailyCounter->update(['last_number' => $newNumber]);

            // 3. Format the queue number (e.g., A-001)
            $formattedNumber = $serviceType->code . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            // 4. Create the queue record
            $queue = Queue::create([
                'institution_id' => $serviceType->institution_id,
                'service_type_id' => $serviceType->id,
                'queue_number' => $formattedNumber,
                'customer_name' => $customerName,
                'status' => 'waiting',
                'queue_date' => $today,
            ]);

            event(new \App\Events\QueueCreated($queue));

            return $queue;
        });
    }

    /**
     * Get the estimated waiting time for a service.
     */
    public function getEstimatedWaitTime(ServiceType $serviceType): int
    {
        $waitingCount = Queue::where('service_type_id', $serviceType->id)
            ->today()
            ->waiting()
            ->count();

        // Assume average 5 minutes per customer if not specified
        return $waitingCount * 5; 
    }

    /**
     * Get the current active queue for a service.
     */
    public function getCurrentQueue(ServiceType $serviceType): ?Queue
    {
        return Queue::where('service_type_id', $serviceType->id)
            ->today()
            ->active()
            ->latest('called_at')
            ->first();
    }

    /**
     * Call the next customer in line for a specific counter.
     */
    public function callNext(\App\Models\Counter $counter): ?Queue
    {
        return DB::transaction(function () use ($counter) {
            // 1. Complete any current active queue for this counter
            Queue::where('counter_id', $counter->id)
                ->active()
                ->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

            // 2. Find the next waiting queue for the assigned service type
            $nextQueue = Queue::where('service_type_id', $counter->service_type_id)
                ->today()
                ->waiting()
                ->oldest()
                ->first();

            if (!$nextQueue) {
                return null;
            }

            // 3. Assign to counter and set calling status
            $nextQueue->update([
                'counter_id' => $counter->id,
                'status' => 'calling',
                'called_at' => now(),
                'served_at' => now(),
                'recall_count' => 0,
            ]);

            event(new \App\Events\QueueCalled($nextQueue));

            return $nextQueue;
        });
    }

    /**
     * Recall a customer.
     */
    public function recall(Queue $queue): Queue
    {
        $queue->increment('recall_count');
        $queue->update(['called_at' => now()]);
        
        event(new \App\Events\QueueCalled($queue));

        return $queue;
    }

    /**
     * Complete a service.
     */
    public function complete(Queue $queue): Queue
    {
        $queue->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        return $queue;
    }

    /**
     * Skip a customer.
     */
    public function skip(Queue $queue): Queue
    {
        $queue->update([
            'status' => 'skipped',
            'completed_at' => now(), 
        ]);
        return $queue;
    }

    /**
     * Transfer a customer to another service.
     */
    public function transfer(Queue $queue, ServiceType $newServiceType): Queue
    {
        return DB::transaction(function () use ($queue, $newServiceType) {
            $today = Carbon::today()->toDateString();

            // 1. Get with lock or create daily counter for the target service
            $dailyCounter = DailyCounter::where('institution_id', $newServiceType->institution_id)
                ->where('service_type_id', $newServiceType->id)
                ->whereDate('date', $today)
                ->lockForUpdate()
                ->first();

            if (!$dailyCounter) {
                $dailyCounter = DailyCounter::create([
                    'institution_id' => $newServiceType->institution_id,
                    'service_type_id' => $newServiceType->id,
                    'date' => $today,
                    'last_number' => 0,
                ]);
            }

            // 2. Increment target number
            $newNumber = $dailyCounter->last_number + 1;
            $dailyCounter->update(['last_number' => $newNumber]);

            // 3. Format the new queue number (e.g., B-001)
            $formattedNumber = $newServiceType->code . '-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

            // 4. Update the queue record
            $queue->update([
                'service_type_id' => $newServiceType->id,
                'queue_number' => $formattedNumber,
                'status' => 'waiting',
                'counter_id' => null,
                'called_at' => null,
                'served_at' => null,
                'completed_at' => null,
                'recall_count' => 0,
            ]);

            event(new \App\Events\QueueCreated($queue));

            return $queue;
        });
    }
}
