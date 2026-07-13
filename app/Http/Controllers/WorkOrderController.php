<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    public function index()
    {
        try {
            $orders = DB::select("
                SELECT w.order_id, w.title, w.status,
                       TO_CHAR(w.created_at, 'DD Mon YYYY') AS created_date,
                       s.ship_name
                FROM work_orders w
                JOIN ships s ON w.ship_id = s.ship_id
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

            $activeCount = DB::selectOne("
                SELECT COUNT(*) AS cnt FROM work_orders
                WHERE status IN ('pending', 'in_progress')
            ")->cnt;

        } catch (\Exception $e) {
            $orders           = [];
            $workersByOrder   = [];
            $materialsByOrder = [];
            $activeCount      = 0;
        }

        return view('work-orders.index', compact(
            'orders', 'workersByOrder', 'materialsByOrder', 'activeCount'
        ));
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

    // Calls the Oracle stored procedure create_work_order via PDO.
    public function store(Request $request)
    {
        $request->validate([
            'ship_id'     => 'required|integer',
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $pdo    = DB::getPdo();
        $result = '';

        $stmt = $pdo->prepare(
            "BEGIN create_work_order(:p_ship_id, :p_title, :p_desc,
                TO_DATE(:p_start, 'YYYY-MM-DD'), TO_DATE(:p_end, 'YYYY-MM-DD'),
                :p_result); END;"
        );

        $shipId = (int) $request->ship_id;
        $title  = $request->title;
        $desc   = $request->description ?? '';
        $start  = $request->start_date;
        $end    = $request->end_date;

        $stmt->bindParam(':p_ship_id', $shipId, \PDO::PARAM_INT);
        $stmt->bindParam(':p_title',   $title,  \PDO::PARAM_STR);
        $stmt->bindParam(':p_desc',    $desc,   \PDO::PARAM_STR);
        $stmt->bindParam(':p_start',   $start,  \PDO::PARAM_STR);
        $stmt->bindParam(':p_end',     $end,    \PDO::PARAM_STR);
        $stmt->bindParam(':p_result',  $result, \PDO::PARAM_STR | \PDO::PARAM_INPUT_OUTPUT, 200);

        $stmt->execute();

        if (str_starts_with($result, 'ERROR')) {
            return back()->withInput()->withErrors(['oracle' => $result]);
        }

        return redirect()->route('work-orders.index')->with('success', 'Work order created successfully.');
    }
}
