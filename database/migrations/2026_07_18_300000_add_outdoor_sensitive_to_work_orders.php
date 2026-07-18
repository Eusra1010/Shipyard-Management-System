<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE work_orders ADD is_outdoor_sensitive NUMBER(1) DEFAULT 0 NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE work_orders DROP COLUMN is_outdoor_sensitive");
    }
};
