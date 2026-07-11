<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
  
        DB::unprepared("
            CREATE OR REPLACE FUNCTION get_active_jobs(p_ship_id IN NUMBER)
            RETURN NUMBER
            IS
                v_count NUMBER := 0;
            BEGIN
                SELECT COUNT(*) INTO v_count
                FROM work_orders
                WHERE ship_id = p_ship_id
                  AND status  != 'done';
                RETURN v_count;
            EXCEPTION
                WHEN OTHERS THEN
                    RETURN 0;
            END get_active_jobs;
        ");

        DB::unprepared("
            CREATE OR REPLACE PROCEDURE create_work_order(
                p_ship_id     IN  NUMBER,
                p_title       IN  VARCHAR2,
                p_description IN  VARCHAR2,
                p_start_date  IN  DATE,
                p_end_date    IN  DATE,
                p_result      OUT VARCHAR2
            )
            IS
                v_ship_count  NUMBER      := 0;
                v_new_id      NUMBER      := 0;
                v_status      VARCHAR2(20);
                v_days        NUMBER;
                v_log_msg     VARCHAR2(200);
            BEGIN
                SELECT COUNT(*) INTO v_ship_count FROM ships WHERE ship_id = p_ship_id;

                IF v_ship_count = 0 THEN
                    p_result := 'ERROR: Ship not found';
                    RETURN;
                END IF;

                v_days := p_end_date - p_start_date;

                v_status := CASE
                    WHEN p_start_date <= SYSDATE THEN 'in_progress'
                    WHEN p_start_date >  SYSDATE THEN 'pending'
                    ELSE 'pending'
                END;

                SELECT NVL(MAX(order_id), 0) + 1 INTO v_new_id FROM work_orders;

                INSERT INTO work_orders(order_id, ship_id, title, description, status,
                                        start_date, end_date, created_at)
                VALUES(v_new_id, p_ship_id, p_title, p_description, v_status,
                       p_start_date, p_end_date, SYSDATE);

                v_log_msg := 'Order #' || TO_CHAR(v_new_id) || ' created for ship ' || TO_CHAR(p_ship_id);
                p_result  := 'OK: ' || v_log_msg;

                COMMIT;
            EXCEPTION
                WHEN OTHERS THEN
                    ROLLBACK;
                    p_result := 'ERROR: ' || SQLERRM;
            END create_work_order;
        ");

        
        DB::unprepared("
            CREATE OR REPLACE PROCEDURE list_ship_jobs(p_ship_id IN NUMBER)
            IS
                v_total  NUMBER := 0;
                CURSOR c_jobs IS
                    SELECT order_id, title, status
                    FROM work_orders
                    WHERE ship_id = p_ship_id
                    ORDER BY order_id;
            BEGIN
                FOR rec IN c_jobs LOOP
                    v_total := v_total + 1;
                    IF rec.status = 'in_progress' OR rec.status = 'pending' THEN
                        DBMS_OUTPUT.PUT_LINE('Active [' || TO_CHAR(v_total) || '] ' || rec.title);
                    ELSE
                        DBMS_OUTPUT.PUT_LINE('Done   [' || TO_CHAR(v_total) || '] ' || rec.title);
                    END IF;
                END LOOP;
                DBMS_OUTPUT.PUT_LINE('--- Total jobs: ' || TO_CHAR(v_total));
            EXCEPTION
                WHEN OTHERS THEN
                    DBMS_OUTPUT.PUT_LINE('Error: ' || SQLERRM);
            END list_ship_jobs;
        ");

        // TRIGGER: trg_berth_status
        // PL/SQL Topics: AFTER UPDATE trigger, :NEW and :OLD pseudo-records, IF-THEN
        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_berth_status
            AFTER UPDATE OF status ON ships
            FOR EACH ROW
            BEGIN
                IF :NEW.status = 'departed' AND :OLD.status != 'departed' THEN
                    UPDATE berths
                    SET    status  = 'free',
                           ship_id = NULL
                    WHERE  ship_id = :NEW.ship_id;
                END IF;
            END trg_berth_status;
        ");

        // TRIGGER: trg_reduce_stock
        // PL/SQL Topics: AFTER INSERT trigger, :NEW pseudo-record, arithmetic subtraction
        DB::unprepared("
            CREATE OR REPLACE TRIGGER trg_reduce_stock
            AFTER INSERT ON material_usage
            FOR EACH ROW
            BEGIN
                UPDATE materials
                SET    quantity = quantity - :NEW.qty_used
                WHERE  material_id = :NEW.material_id;
            END trg_reduce_stock;
        ");
    }

    public function down(): void
    {
        foreach (['trg_reduce_stock', 'trg_berth_status'] as $trg) {
            try { DB::unprepared("DROP TRIGGER {$trg}"); } catch (\Exception $e) {}
        }
        foreach (['list_ship_jobs', 'create_work_order'] as $proc) {
            try { DB::unprepared("DROP PROCEDURE {$proc}"); } catch (\Exception $e) {}
        }
        try { DB::unprepared("DROP FUNCTION get_active_jobs"); } catch (\Exception $e) {}
    }
};
