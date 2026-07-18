<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE users ADD team VARCHAR2(50)");
        DB::statement("ALTER TABLE users ADD status VARCHAR2(20) DEFAULT 'available'");

        // Promote one staff account to supervisor for demo
        DB::update("UPDATE users SET role = 'supervisor', team = 'Welding' WHERE id = 21");
        DB::update("UPDATE users SET team = 'Welding' WHERE id = 22");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users DROP COLUMN team");
        DB::statement("ALTER TABLE users DROP COLUMN status");
    }
};
