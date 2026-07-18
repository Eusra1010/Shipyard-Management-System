<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    public function index()
    {
        try {
            $stats = DB::selectOne("
                SELECT
                    COUNT(*) AS total,
                    SUM(CASE WHEN status = 'pending'     THEN 1 ELSE 0 END) AS pending_count,
                    SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) AS in_progress_count,
                    SUM(CASE WHEN status = 'done'        THEN 1 ELSE 0 END) AS done_count
                FROM work_orders
            ");

            $orders = DB::select("
                SELECT w.order_id, w.title, w.status,
                       TO_CHAR(w.start_date, 'DD/MM/YYYY') AS start_date,
                       TO_CHAR(w.end_date,   'DD/MM/YYYY') AS end_date,
                       TO_CHAR(w.created_at, 'DD Mon YYYY') AS created_date,
                       s.ship_name,
                       NVL(b.berth_name, '—') AS berth_name
                FROM work_orders w
                JOIN ships s ON w.ship_id = s.ship_id
                LEFT JOIN berths b ON b.ship_id = s.ship_id
                ORDER BY w.created_at DESC
            ");

            $workerRows = DB::select("
                SELECT ww.order_id, wk.name, wk.role
                FROM work_order_workers ww
                JOIN workers wk ON wk.worker_id = ww.worker_id
                ORDER BY ww.order_id, wk.name
            ");

            $materialRows = DB::select("
                SELECT mu.order_id, m.name, mu.qty_used, m.unit
                FROM material_usage mu
                JOIN materials m ON m.material_id = mu.material_id
                ORDER BY mu.order_id, m.name
            ");

            $workersByOrder   = [];
            foreach ($workerRows as $row) {
                $workersByOrder[$row->order_id][] = $row;
            }
            $materialsByOrder = [];
            foreach ($materialRows as $row) {
                $materialsByOrder[$row->order_id][] = $row;
            }

            // Data for create modal
            $modalShips = DB::select("
                SELECT s.ship_id, s.ship_name, NVL(b.berth_name, '') AS berth_name
                FROM ships s
                LEFT JOIN berths b ON b.ship_id = s.ship_id
                WHERE s.status = 'docked'
                AND s.ship_id NOT IN (
                    SELECT ship_id FROM work_orders
                    WHERE status != 'done' AND ship_id IS NOT NULL
                )
                ORDER BY s.ship_name
            ");

            $modalWorkers = DB::select("
                SELECT worker_id, name, role FROM workers ORDER BY role, name
            ");

        } catch (\Exception $e) {
            $stats            = (object)['total'=>0,'pending_count'=>0,'in_progress_count'=>0,'done_count'=>0];
            $orders           = [];
            $workersByOrder   = [];
            $materialsByOrder = [];
            $modalShips       = [];
            $modalWorkers     = [];
        }

        return view('work-orders.index', compact(
            'stats', 'orders', 'workersByOrder', 'materialsByOrder',
            'modalShips', 'modalWorkers'
        ));
    }

    public function show($id)
    {
        $order = DB::selectOne("
            SELECT wo.order_id, wo.title, wo.description, wo.status,
                   TO_CHAR(wo.start_date, 'DD/MM/YYYY') AS start_date,
                   TO_CHAR(wo.end_date,   'DD/MM/YYYY') AS end_date,
                   TO_CHAR(wo.created_at, 'DD Mon YYYY') AS created_at,
                   s.ship_name, s.ship_type, s.flag,
                   NVL(b.name, '—') AS berth_name
            FROM work_orders wo
            JOIN ships s ON s.ship_id = wo.ship_id
            LEFT JOIN berths b ON b.ship_id = s.ship_id
            WHERE wo.order_id = :id
        ", ['id' => $id]);

        if (!$order) abort(404);

        $workers = DB::select("
            SELECT wk.worker_id, wk.name, wk.role, wk.phone
            FROM work_order_workers ww
            JOIN workers wk ON wk.worker_id = ww.worker_id
            WHERE ww.order_id = :id
            ORDER BY wk.role, wk.name
        ", ['id' => $id]);

        $materials = DB::select("
            SELECT m.name, m.unit, mu.qty_used
            FROM material_usage mu
            JOIN materials m ON m.material_id = mu.material_id
            WHERE mu.order_id = :id
            ORDER BY m.name
        ", ['id' => $id]);

        return view('work-orders.show', compact('order', 'workers', 'materials'));
    }

    public function edit($id)
    {
        $order = DB::selectOne("
            SELECT wo.order_id, wo.title, wo.description, wo.status,
                   TO_CHAR(wo.start_date, 'YYYY-MM-DD') AS start_date,
                   TO_CHAR(wo.end_date,   'YYYY-MM-DD') AS end_date,
                   wo.is_outdoor_sensitive,
                   s.ship_name
            FROM work_orders wo
            JOIN ships s ON s.ship_id = wo.ship_id
            WHERE wo.order_id = :id
        ", ['id' => $id]);

        if (!$order) abort(404);

        $allWorkers = DB::select("
            SELECT worker_id, name, role
            FROM workers
            ORDER BY role, name
        ");

        $assignedRows = DB::select("
            SELECT worker_id FROM work_order_workers WHERE order_id = :id
        ", ['id' => $id]);
        $assignedIds = array_column($assignedRows, 'worker_id');

        $allMaterials = DB::select("
            SELECT material_id, name, unit FROM materials ORDER BY name
        ");

        $usedMaterials = DB::select("
            SELECT material_id, qty_used
            FROM material_usage
            WHERE order_id = :id
        ", ['id' => $id]);

        return view('work-orders.edit', compact(
            'order', 'allWorkers', 'assignedIds', 'allMaterials', 'usedMaterials'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'      => 'required|max:200',
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
            'status'     => 'required|in:pending,in_progress,done',
        ]);

        DB::update("
            UPDATE work_orders
            SET title = :title,
                description = :desc,
                start_date = TO_DATE(:start, 'YYYY-MM-DD'),
                end_date   = TO_DATE(:end,   'YYYY-MM-DD'),
                status = :status,
                is_outdoor_sensitive = :outdoor
            WHERE order_id = :id
        ", [
            'title'   => $request->title,
            'desc'    => $request->description ?? '',
            'start'   => $request->start_date,
            'end'     => $request->end_date,
            'status'  => $request->status,
            'outdoor' => $request->boolean('is_outdoor_sensitive') ? 1 : 0,
            'id'      => $id,
        ]);

        // Reassign workers
        DB::delete("DELETE FROM work_order_workers WHERE order_id = :id", ['id' => $id]);
        foreach ($request->input('worker_ids', []) as $wid) {
            DB::insert(
                "INSERT INTO work_order_workers (order_id, worker_id) VALUES (:oid, :wid)",
                ['oid' => $id, 'wid' => $wid]
            );
        }

        // Update material usage
        DB::delete("DELETE FROM material_usage WHERE order_id = :id", ['id' => $id]);
        $matIds = $request->input('material_id', []);
        $matQty = $request->input('qty_used', []);
        foreach ($matIds as $i => $mid) {
            if (!$mid || !isset($matQty[$i]) || (float)$matQty[$i] <= 0) continue;
            $usageId = DB::selectOne("SELECT NVL(MAX(usage_id),0)+1 AS nxt FROM material_usage")->nxt;
            DB::insert(
                "INSERT INTO material_usage (usage_id, order_id, material_id, qty_used)
                 VALUES (:uid, :oid, :mid, :qty)",
                ['uid' => $usageId, 'oid' => $id, 'mid' => $mid, 'qty' => (float)$matQty[$i]]
            );
        }

        return redirect()->route('work-orders.show', $id)->with('success', 'Work order updated.');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:pending,in_progress,done']);

        DB::update(
            "UPDATE work_orders SET status = :status WHERE order_id = :id",
            ['status' => $request->status, 'id' => $id]
        );

        if ($request->input('from') === 'supervisor') {
            return redirect()->route('dashboard')->with('success', 'Work order status updated.');
        }

        return redirect()->route('work-orders.show', $id)->with('success', 'Status updated.');
    }

    public function create()
    {
        $ships = DB::select("
            SELECT ship_id, ship_name FROM ships
            WHERE status != 'departed'
            ORDER BY ship_name
        ");
        return view('work-orders.create', compact('ships'));
    }

    public function destroy($id)
    {
        $order = DB::selectOne("SELECT order_id FROM work_orders WHERE order_id = :id", ['id' => $id]);
        if (!$order) abort(404);

        DB::delete("DELETE FROM work_order_workers WHERE order_id = :id", ['id' => $id]);
        DB::delete("DELETE FROM material_usage WHERE order_id = :id", ['id' => $id]);
        DB::delete("DELETE FROM work_orders WHERE order_id = :id", ['id' => $id]);

        return redirect()->route('work-orders.index')->with('success', 'Work order deleted.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ship_id'     => 'required|integer',
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'end_date'    => 'required|date',
            'priority'    => 'nullable|in:normal,urgent',
        ]);

        $orderId = DB::selectOne("SELECT NVL(MAX(order_id),0)+1 AS nxt FROM work_orders")->nxt;

        DB::insert("
            INSERT INTO work_orders
                (order_id, ship_id, title, description, status, priority, start_date, end_date,
                 is_outdoor_sensitive, created_at)
            VALUES
                (:id, :ship, :title, :desc, 'pending', :priority, SYSDATE,
                 TO_DATE(:end, 'YYYY-MM-DD'), :outdoor, SYSDATE)
        ", [
            'id'       => $orderId,
            'ship'     => (int) $request->ship_id,
            'title'    => $request->title,
            'desc'     => $request->description ?? '',
            'priority' => $request->input('priority', 'normal'),
            'end'      => $request->end_date,
            'outdoor'  => $request->boolean('is_outdoor_sensitive') ? 1 : 0,
        ]);

        foreach ($request->input('worker_ids', []) as $wid) {
            DB::insert(
                "INSERT INTO work_order_workers (order_id, worker_id) VALUES (:oid, :wid)",
                ['oid' => $orderId, 'wid' => $wid]
            );
        }

        return redirect()->route('work-orders.index')->with('success', 'Work order created.');
    }
}
