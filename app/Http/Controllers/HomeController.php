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

        } catch (\Exception $e) {
            $totalShips    = 0;
            $shipsInRepair = 0;
            $activeJobs    = 0;
            $freeBerths    = 0;
        }

        return view('home', compact('totalShips', 'shipsInRepair', 'activeJobs', 'freeBerths'));
    }
}
