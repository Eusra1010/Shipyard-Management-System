<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add columns to berths
        DB::statement("ALTER TABLE berths ADD docked_since DATE");
        DB::statement("ALTER TABLE berths ADD berth_type VARCHAR2(20) DEFAULT 'Standard'");

        // Update existing occupied berth
        DB::update("UPDATE berths SET docked_since = TO_DATE('2026-06-15','YYYY-MM-DD') WHERE berth_id = 1 AND status = 'occupied'");

        // Add extra ships for demo berth data
        DB::insert("INSERT INTO ships (ship_id,ship_name,ship_type,owner_name,tonnage,flag_country,status,arrival_date,created_at,updated_at) VALUES (5,'MV Atlantic Pride','Bulk Carrier','Chowdhury Shipping',18500,'BD','docked',TO_DATE('2026-07-02','YYYY-MM-DD'),SYSTIMESTAMP,SYSTIMESTAMP)");
        DB::insert("INSERT INTO ships (ship_id,ship_name,ship_type,owner_name,tonnage,flag_country,status,arrival_date,created_at,updated_at) VALUES (6,'MV Horizon','Container','Horizon Lines Ltd',22000,'SG','docked',TO_DATE('2026-07-06','YYYY-MM-DD'),SYSTIMESTAMP,SYSTIMESTAMP)");
        DB::insert("INSERT INTO ships (ship_id,ship_name,ship_type,owner_name,tonnage,flag_country,status,arrival_date,created_at,updated_at) VALUES (7,'TK Sea Titan','Tanker','Gulf Maritime Co',31000,'AE','docked',TO_DATE('2026-07-09','YYYY-MM-DD'),SYSTIMESTAMP,SYSTIMESTAMP)");
        DB::insert("INSERT INTO ships (ship_id,ship_name,ship_type,owner_name,tonnage,flag_country,status,arrival_date,created_at,updated_at) VALUES (8,'MV Bay Spirit','General Cargo','Bay Carriers Ltd',9500,'BD','docked',TO_DATE('2026-07-11','YYYY-MM-DD'),SYSTIMESTAMP,SYSTIMESTAMP)");

        // Add berths 4-10 (berth_id 10 = Dry Dock)
        DB::insert("INSERT INTO berths (berth_id,berth_name,status,ship_id,docked_since,berth_type) VALUES (4,'Berth 4','occupied',2,TO_DATE('2026-06-20','YYYY-MM-DD'),'Standard')");
        DB::insert("INSERT INTO berths (berth_id,berth_name,status,ship_id,docked_since,berth_type) VALUES (5,'Berth 5','occupied',3,TO_DATE('2026-07-01','YYYY-MM-DD'),'Standard')");
        DB::insert("INSERT INTO berths (berth_id,berth_name,status,ship_id,docked_since,berth_type) VALUES (6,'Berth 6','free',NULL,NULL,'Standard')");
        DB::insert("INSERT INTO berths (berth_id,berth_name,status,ship_id,docked_since,berth_type) VALUES (7,'Berth 7','occupied',5,TO_DATE('2026-07-02','YYYY-MM-DD'),'Standard')");
        DB::insert("INSERT INTO berths (berth_id,berth_name,status,ship_id,docked_since,berth_type) VALUES (8,'Berth 8','occupied',6,TO_DATE('2026-07-06','YYYY-MM-DD'),'Standard')");
        DB::insert("INSERT INTO berths (berth_id,berth_name,status,ship_id,docked_since,berth_type) VALUES (9,'Berth 9','free',NULL,NULL,'Standard')");
        DB::insert("INSERT INTO berths (berth_id,berth_name,status,ship_id,docked_since,berth_type) VALUES (10,'Dry Dock','occupied',7,TO_DATE('2026-07-09','YYYY-MM-DD'),'Dry Dock')");
    }

    public function down(): void
    {
        DB::delete("DELETE FROM berths WHERE berth_id IN (4,5,6,7,8,9,10)");
        DB::delete("DELETE FROM ships  WHERE ship_id  IN (5,6,7,8)");
        DB::statement("ALTER TABLE berths DROP COLUMN docked_since");
        DB::statement("ALTER TABLE berths DROP COLUMN berth_type");
    }
};
