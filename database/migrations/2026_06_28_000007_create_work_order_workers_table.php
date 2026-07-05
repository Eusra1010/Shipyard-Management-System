<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('work_order_workers', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('worker_id')->nullable();

            $table->foreign('order_id')->references('order_id')->on('work_orders')->onDelete('cascade');
            $table->foreign('worker_id')->references('worker_id')->on('workers')->onDelete('cascade');
            $table->unique(['order_id', 'worker_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_order_workers');
    }
};
