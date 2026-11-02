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
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->text('address');
            $table->text('description')->nullable();
            $table->string('phone', 20)->nullable();
            $table->time('opening_time');
            $table->time('closing_time');
            $table->enum('status', ['active', 'inactive'])->default('active');

            // TODO: default belum diputuskan
            $table->boolean('allow_full_online')->default(true);
            $table->boolean('allow_dp')->default(false);
            $table->boolean('allow_onsite')->default(false);
            $table->enum('dp_type', ['percent', 'fixed'])->nullable()->default('percent');
            $table->decimal('dp_value', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
