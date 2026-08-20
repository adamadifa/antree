<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('counter_id')->nullable()->constrained()->onDelete('set null');
            $table->string('queue_number')->comment('e.g. A-001');
            $table->string('customer_name')->nullable();
            $table->enum('status', ['waiting', 'calling', 'serving', 'completed', 'skipped', 'cancelled'])->default('waiting');
            $table->timestamp('called_at')->nullable();
            $table->timestamp('served_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('recall_count')->default(0);
            $table->date('queue_date');
            $table->timestamps();

            $table->index(['queue_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('queues');
    }
};
