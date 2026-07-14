-- ============================================================
-- NavalForge PL/SQL Objects
-- Run this in SQL*Plus as the navalforge user.
-- ============================================================

-- Topic 1 & 2: Introduction to PL/SQL + Block Structure
--   Every object below uses the DECLARE/BEGIN/EXCEPTION/END block.
--   PL/SQL is Oracle's procedural extension to SQL.
--   A PL/SQL block has three optional sections:
--     DECLARE  -- variable declarations
--     BEGIN    -- executable statements
--     EXCEPTION -- error handling
--     END;

-- ============================================================
-- FUNCTION: get_active_jobs
-- Topic 3 (operators): :=  =  !=
-- Topic 6 (functions): RETURN NUMBER
-- ============================================================
CREATE OR REPLACE FUNCTION get_active_jobs(p_ship_id IN NUMBER)
RETURN NUMBER
IS
    v_count NUMBER := 0;   -- := is the assignment operator
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
/


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
    -- Validate: ship must exist
    SELECT COUNT(*) INTO v_ship_count
    FROM ships
    WHERE ship_id = p_ship_id; 
    -- IF-THEN: guard clause
    IF v_ship_count = 0 THEN
        p_result := 'ERROR: Ship not found';
        RETURN;
    END IF;

    
    v_days := p_end_date - p_start_date;

   
    v_status := CASE
        WHEN p_start_date <= SYSDATE THEN 'in_progress'  -- <= comparison
        WHEN p_start_date >  SYSDATE THEN 'pending'      -- >  comparison
        ELSE 'pending'
    END;

    -- Generate new ID without a sequence
    SELECT NVL(MAX(order_id), 0) + 1 INTO v_new_id FROM work_orders;

    INSERT INTO work_orders(order_id, ship_id, title, description, status,
                            start_date, end_date, created_at)
    VALUES(v_new_id, p_ship_id, p_title, p_description, v_status,
           p_start_date, p_end_date, SYSDATE);

    -- Concatenation operator ||: build success message
    v_log_msg := 'Order #' || TO_CHAR(v_new_id) || ' created for ship ' || TO_CHAR(p_ship_id);
    p_result  := 'OK: ' || v_log_msg;

    COMMIT;
EXCEPTION
    WHEN OTHERS THEN
        ROLLBACK;
        p_result := 'ERROR: ' || SQLERRM;  -- || concatenation in exception handler
END create_work_order;
/

-- ============================================================
-- PROCEDURE: list_ship_jobs
-- Topic 4 (flow control): cursor FOR LOOP, IF-THEN-ELSE, OR logical operator
-- Topic 5 (procedures): no OUT parameter; uses DBMS_OUTPUT
-- ============================================================
CREATE OR REPLACE PROCEDURE list_ship_jobs(p_ship_id IN NUMBER)
IS
    v_total  NUMBER := 0;   -- counts iterations via arithmetic
    CURSOR c_jobs IS
        SELECT order_id, title, status
        FROM work_orders
        WHERE ship_id = p_ship_id
        ORDER BY order_id;
BEGIN
    -- Cursor FOR LOOP: Oracle opens, fetches, and closes automatically
    FOR rec IN c_jobs LOOP
        v_total := v_total + 1;     -- arithmetic operator +

        -- IF-THEN-ELSE with logical OR operator
        IF rec.status = 'in_progress' OR rec.status = 'pending' THEN
            DBMS_OUTPUT.PUT_LINE('Active [' || TO_CHAR(v_total) || '] ' || rec.title);
        ELSE
            DBMS_OUTPUT.PUT_LINE('Done   [' || TO_CHAR(v_total) || '] ' || rec.title);
        END IF;
    END LOOP;

    -- Arithmetic: show total at end
    DBMS_OUTPUT.PUT_LINE('--- Total jobs: ' || TO_CHAR(v_total));
EXCEPTION
    WHEN OTHERS THEN
        DBMS_OUTPUT.PUT_LINE('Error: ' || SQLERRM);
END list_ship_jobs;
/

-- ============================================================
-- TRIGGER: trg_berth_status
-- Topic 7 (triggers): AFTER UPDATE, :NEW, :OLD, IF-THEN
-- Fires when a ship departs; automatically frees its berth.
-- ============================================================
CREATE OR REPLACE TRIGGER trg_berth_status
AFTER UPDATE OF status ON ships
FOR EACH ROW
BEGIN
    -- :OLD.status = value before update, :NEW.status = value after
    IF :NEW.status = 'departed' AND :OLD.status != 'departed' THEN
        -- Free the berth
        UPDATE berths
        SET    status  = 'free',
               ship_id = NULL
        WHERE  ship_id = :NEW.ship_id;

        -- Close all active work orders for the departing ship
        UPDATE work_orders
        SET    status = 'done'
        WHERE  ship_id = :NEW.ship_id
          AND  status IN ('pending', 'in_progress');
    END IF;
END trg_berth_status;
/


CREATE OR REPLACE TRIGGER trg_reduce_stock
AFTER INSERT ON material_usage
FOR EACH ROW
BEGIN
    UPDATE materials
    SET    quantity = quantity - :NEW.qty_used 
    WHERE  material_id = :NEW.material_id;
END trg_reduce_stock;
/
