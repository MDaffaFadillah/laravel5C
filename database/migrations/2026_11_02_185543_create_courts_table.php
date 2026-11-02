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
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->restrictOnDelete();
            $table->string('name', 100);
            $table->enum('sport_type', ['futsal', 'badminton']);
            $table->text('description')->nullable();
            $table->decimal('price_per_hour', 12, 2);
            $table->enum('status', ['available', 'maintenance', 'inactive'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
