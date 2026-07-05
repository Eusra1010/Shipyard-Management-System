<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('berths', function (Blueprint $table) {
            $table->bigInteger('berth_id')->primary();
            $table->string('berth_name', 50);
            $table->enum('status', ['free', 'occupied'])->default('free');
            $table->bigInteger('ship_id')->nullable();

            $table->foreign('ship_id')->references('ship_id')->on('ships')->onDelete('set null');
            $table->index('ship_id');
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('berths');
    }
};
