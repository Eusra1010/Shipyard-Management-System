<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE work_orders ADD priority VARCHAR2(10) DEFAULT 'normal'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE work_orders DROP COLUMN priority");
    }
};
