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
        Schema::create('institutions', function (Blueprint $box) {
            $box->id();
            $box->string('name');
            $box->string('address')->nullable();
            $box->string('phone')->nullable();
            $box->string('logo_path')->nullable();
            $box->text('operating_hours')->nullable();
            $box->boolean('is_active')->default(true);
            $box->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
