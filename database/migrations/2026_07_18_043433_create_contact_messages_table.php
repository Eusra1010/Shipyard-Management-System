<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE contact_messages (
                id         NUMBER PRIMARY KEY,
                name       VARCHAR2(255) NOT NULL,
                email      VARCHAR2(255) NOT NULL,
                subject    VARCHAR2(255) NOT NULL,
                message    CLOB NOT NULL,
                is_read    NUMBER(1) DEFAULT 0 NOT NULL,
                created_at DATE DEFAULT SYSDATE NOT NULL
            )
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE contact_messages");
    }
};
