<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $activeShips    = DB::selectOne("SELECT COUNT(*) AS cnt FROM ships WHERE status = 'in_repair'")->cnt;
            $totalBerths    = DB::selectOne("SELECT COUNT(*) AS cnt FROM berths")->cnt;
            $freeBerths     = DB::selectOne("SELECT COUNT(*) AS cnt FROM berths WHERE status = 'free'")->cnt;
            $occupiedBerths = $totalBerths - $freeBerths;
            $activeOrders   = DB::selectOne("SELECT COUNT(*) AS cnt FROM work_orders WHERE status != 'done'")->cnt;
            $lowStockCount  = DB::selectOne("SELECT COUNT(*) AS cnt FROM materials WHERE quantity < 10")->cnt;

            $berthGrid = DB::select("SELECT berth_id, berth_name, status FROM berths ORDER BY berth_name");

            $recentOrders = DB::select("
                SELECT * FROM (
                    SELECT w.order_id, w.title, w.status, s.ship_name
                    FROM work_orders w
                    JOIN ships s ON w.ship_id = s.ship_id
                    WHERE w.status != 'done'
                    ORDER BY w.created_at DESC
                ) WHERE ROWNUM <= 10
            ");

            $lowStockMaterials = DB::select("
                SELECT * FROM (
                    SELECT name, quantity, unit
                    FROM materials
                    WHERE quantity < 10
                    ORDER BY quantity ASC
                ) WHERE ROWNUM <= 8
            ");

            $totalWorkers    = DB::selectOne("SELECT COUNT(*) AS cnt FROM workers")->cnt;
            $assignedWorkers = DB::selectOne("
                SELECT COUNT(DISTINCT ww.worker_id) AS cnt
                FROM work_order_workers ww
                JOIN work_orders wo ON ww.order_id = wo.order_id
                WHERE wo.status != 'done'
            ")->cnt;

        } catch (\Exception $e) {
            $activeShips       = 0;
            $totalBerths       = 0;
            $freeBerths        = 0;
            $occupiedBerths    = 0;
            $activeOrders      = 0;
            $lowStockCount     = 0;
            $berthGrid         = [];
            $recentOrders      = [];
            $lowStockMaterials = [];
            $totalWorkers      = 0;
            $assignedWorkers   = 0;
        }

        return view('dashboard', compact(
            'activeShips', 'totalBerths', 'freeBerths', 'occupiedBerths',
            'activeOrders', 'lowStockCount', 'berthGrid', 'recentOrders',
            'lowStockMaterials', 'totalWorkers', 'assignedWorkers'
        ));
    }
}
