<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->bigInteger('order_id')->primary();
            $table->bigInteger('ship_id');
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'done', 'cancelled'])->default('pending');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('ship_id')->references('ship_id')->on('ships');
            $table->index('ship_id');
            $table->index('status');
        });
    }

   
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
