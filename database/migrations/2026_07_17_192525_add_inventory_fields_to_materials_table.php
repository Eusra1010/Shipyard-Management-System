<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE materials ADD category VARCHAR2(50) DEFAULT 'General'");
        DB::statement("ALTER TABLE materials ADD min_threshold NUMBER(10) DEFAULT 0");
        DB::statement("ALTER TABLE materials ADD last_restocked DATE");

        DB::update("UPDATE materials SET category = 'Steel',     min_threshold = 50,  last_restocked = TO_DATE('2026-06-01','YYYY-MM-DD') WHERE material_id = 1");
        DB::update("UPDATE materials SET category = 'Paint',     min_threshold = 30,  last_restocked = TO_DATE('2026-05-15','YYYY-MM-DD') WHERE material_id = 2");
        DB::update("UPDATE materials SET category = 'Fasteners', min_threshold = 200, last_restocked = TO_DATE('2026-06-20','YYYY-MM-DD') WHERE material_id = 3");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE materials DROP COLUMN category");
        DB::statement("ALTER TABLE materials DROP COLUMN min_threshold");
        DB::statement("ALTER TABLE materials DROP COLUMN last_restocked");
    }
};
