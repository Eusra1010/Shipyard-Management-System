<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   
    public function up(): void
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->bigInteger('ship_id')->primary();
            $table->string('ship_name', 100);
            $table->string('ship_type', 50)->nullable();
            $table->string('owner_name', 100)->nullable();
            $table->integer('tonnage')->nullable();
            $table->string('flag_country', 50)->nullable();
            $table->enum('status', ['docked', 'in_repair', 'departed'])->default('docked');
            $table->date('arrival_date')->nullable();
            $table->timestamps();
        });
    }

    
    public function down(): void
    {
        Schema::dropIfExists('ships');
    }
};
