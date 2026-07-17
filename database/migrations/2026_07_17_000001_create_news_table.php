<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            CREATE TABLE news (
                id          NUMBER(10)     PRIMARY KEY,
                title       VARCHAR2(200)  NOT NULL,
                description VARCHAR2(2000),
                published_at DATE           NOT NULL,
                image_path  VARCHAR2(500),
                link        VARCHAR2(500),
                pdf_path    VARCHAR2(500),
                created_at  DATE,
                updated_at  DATE
            )
        ");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE news");
    }
};
