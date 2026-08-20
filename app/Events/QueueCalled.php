<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueCalled implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $queueNumber;
    public $counterName;
    public $serviceName;
    public $counterId;

    /**
     * Create a new event instance.
     */
    public function __construct(\App\Models\Queue $queue)
    {
        $this->queueNumber = $queue->queue_number;
        $this->counterName = $queue->counter->name;
        $this->serviceName = $queue->serviceType->name;
        $this->counterId = $queue->counter_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('queue-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'queue.called';
    }
}
