<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ProjectController extends Controller
{
    public function index()
    {
        try {
          
            $ongoing = DB::select("
                SELECT w.order_id, w.title, w.status, w.start_date,
                       s.ship_name, s.ship_type, s.flag_country,
                       b.berth_name,
                       COUNT(ww.worker_id) AS assigned_workers
                FROM work_orders w
                JOIN ships s ON w.ship_id = s.ship_id
                LEFT JOIN berths b ON b.ship_id = s.ship_id
                LEFT JOIN work_order_workers ww ON ww.order_id = w.order_id
                WHERE w.status != 'done'
                GROUP BY w.order_id, w.title, w.status, w.start_date,
                         s.ship_name, s.ship_type, s.flag_country, b.berth_name
                ORDER BY w.start_date DESC
            ");

           
            $completed = DB::select("
                SELECT w.order_id, w.title, w.start_date, w.end_date,
                       s.ship_name, s.ship_type, s.flag_country,
                       b.berth_name,
                       (w.end_date - w.start_date) AS days_taken
                FROM work_orders w
                JOIN ships s ON w.ship_id = s.ship_id
                LEFT JOIN berths b ON b.ship_id = s.ship_id
                WHERE w.status = 'done'
                ORDER BY w.end_date DESC
            ");
        } catch (\Exception $e) {
            $ongoing   = [];
            $completed = [];
        }

        return view('projects', compact('ongoing', 'completed'));
    }
}
