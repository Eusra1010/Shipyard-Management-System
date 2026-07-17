<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        try {
            $ongoing = DB::select("
                SELECT w.order_id, w.ship_id, w.title, w.status,
                       TO_CHAR(w.start_date, 'DD Mon YYYY') AS start_date,
                       s.ship_name, s.ship_type, s.flag_country,
                       b.berth_name,
                       COUNT(ww.worker_id) AS assigned_workers
                FROM work_orders w
                JOIN ships s ON w.ship_id = s.ship_id
                LEFT JOIN berths b ON b.ship_id = s.ship_id
                LEFT JOIN work_order_workers ww ON ww.order_id = w.order_id
                WHERE w.status != 'done'
                GROUP BY w.order_id, w.ship_id, w.title, w.status, w.start_date,
                         s.ship_name, s.ship_type, s.flag_country, b.berth_name
                ORDER BY w.start_date DESC
            ");

            $completed = DB::select("
                SELECT w.order_id, w.ship_id, w.title,
                       TO_CHAR(w.start_date, 'DD Mon YYYY') AS start_date,
                       TO_CHAR(w.end_date,   'DD Mon YYYY') AS end_date,
                       (w.end_date - w.start_date) AS days_taken,
                       s.ship_name, s.ship_type, s.flag_country,
                       b.berth_name
                FROM work_orders w
                JOIN ships s ON w.ship_id = s.ship_id
                LEFT JOIN berths b ON b.ship_id = s.ship_id
                WHERE w.status = 'done'
                ORDER BY w.end_date DESC
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
            foreach ($workerRows as $r) {
                $workersByOrder[$r->order_id][] = $r;
            }

            $materialsByOrder = [];
            foreach ($materialRows as $r) {
                $materialsByOrder[$r->order_id][] = $r;
            }

        } catch (\Exception $e) {
            $ongoing          = [];
            $completed        = [];
            $workersByOrder   = [];
            $materialsByOrder = [];
        }

        return view('projects', compact(
            'ongoing', 'completed', 'workersByOrder', 'materialsByOrder'
        ));
    }
}
