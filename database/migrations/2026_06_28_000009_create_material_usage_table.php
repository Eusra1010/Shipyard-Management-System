<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('material_usage', function (Blueprint $table) {
            $table->bigInteger('usage_id')->primary();
            $table->bigInteger('order_id')->nullable();
            $table->bigInteger('material_id')->nullable();
            $table->integer('qty_used');
            $table->timestamp('used_at')->useCurrent();

            $table->foreign('order_id')->references('order_id')->on('work_orders');
            $table->foreign('material_id')->references('material_id')->on('materials');
            $table->index('material_id');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('material_usage');
    }
};
