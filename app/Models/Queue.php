<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Queue extends Model
{
    protected $fillable = [
        'institution_id',
        'service_type_id',
        'counter_id',
        'queue_number',
        'customer_name',
        'status',
        'called_at',
        'served_at',
        'completed_at',
        'recall_count',
        'queue_date',
    ];

    protected $casts = [
        'called_at' => 'datetime',
        'served_at' => 'datetime',
        'completed_at' => 'datetime',
        'queue_date' => 'date',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function counter(): BelongsTo
    {
        return $this->belongsTo(Counter::class);
    }

    /**
     * Scope for today's queues.
     */
    public function scopeToday(Builder $query): void
    {
        $query->whereDate('queue_date', now()->toDateString());
    }

    /**
     * Scope for waiting queues.
     */
    public function scopeWaiting(Builder $query): void
    {
        $query->where('status', 'waiting');
    }

    /**
     * Scope for active queues (calling or serving).
     */
    public function scopeActive(Builder $query): void
    {
        $query->whereIn('status', ['calling', 'serving']);
    }

    /**
     * Scope for calling status.
     */
    public function scopeCalling(Builder $query): void
    {
        $query->where('status', 'calling');
    }
}
