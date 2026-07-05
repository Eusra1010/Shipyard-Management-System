<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $totalShips    = DB::selectOne("SELECT COUNT(*) AS cnt FROM ships")->cnt;
            $shipsInRepair = DB::selectOne("SELECT COUNT(*) AS cnt FROM ships WHERE status = 'in_repair'")->cnt;
            $activeJobs    = DB::selectOne("SELECT COUNT(*) AS cnt FROM work_orders WHERE status != 'done'")->cnt;
            $freeBerths    = DB::selectOne("SELECT COUNT(*) AS cnt FROM berths WHERE status = 'free'")->cnt;

            $recent = DB::select("
                SELECT * FROM (
                    SELECT w.title, w.status, s.ship_name, b.berth_name
                    FROM work_orders w
                    JOIN ships s ON w.ship_id = s.ship_id
                    LEFT JOIN berths b ON b.ship_id = s.ship_id
                    WHERE w.status != 'done'
                    ORDER BY w.created_at DESC
                ) WHERE ROWNUM <= 3
            ");
        } catch (\Exception $e) {
            $totalShips    = 0;
            $shipsInRepair = 0;
            $activeJobs    = 0;
            $freeBerths    = 0;
            $recent        = [];
        }

        return view('home', compact('totalShips', 'shipsInRepair', 'activeJobs', 'freeBerths', 'recent'));
    }
}
