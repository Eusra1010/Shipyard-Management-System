<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users ADD worker_id NUMBER(10)");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users DROP COLUMN worker_id");
    }
};
