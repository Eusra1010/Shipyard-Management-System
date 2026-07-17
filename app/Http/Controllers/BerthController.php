<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BerthController extends Controller
{
    public function index()
    {
        $berths = DB::select("
            SELECT b.berth_id, b.berth_name, b.status, b.berth_type,
                   TO_CHAR(b.docked_since, 'DD Mon YYYY') AS docked_since,
                   s.ship_name, s.ship_type, s.flag_country,
                   wo.title  AS job_title,
                   wo.status AS job_status
            FROM berths b
            LEFT JOIN ships s ON s.ship_id = b.ship_id
            LEFT JOIN (
                SELECT ship_id, title, status
                FROM (
                    SELECT ship_id, title, status,
                           ROW_NUMBER() OVER (PARTITION BY ship_id ORDER BY created_at DESC) AS rn
                    FROM work_orders
                    WHERE status != 'done'
                )
                WHERE rn = 1
            ) wo ON wo.ship_id = b.ship_id
            ORDER BY b.berth_id
        ");

        $total    = count($berths);
        $occupied = collect($berths)->where('status', 'occupied')->count();
        $free     = $total - $occupied;

        $availableShips = DB::select("
            SELECT s.ship_id, s.ship_name
            FROM ships s
            WHERE s.status = 'docked'
            AND s.ship_id NOT IN (
                SELECT ship_id FROM berths WHERE ship_id IS NOT NULL
            )
            ORDER BY s.ship_name
        ");

        return view('berths.index', compact('berths', 'total', 'free', 'availableShips'));
    }

    public function assign(Request $request, $id)
    {
        $request->validate(['ship_id' => 'required|integer']);

        DB::update("
            UPDATE berths
            SET ship_id = :sid, status = 'occupied', docked_since = SYSDATE
            WHERE berth_id = :bid
        ", ['sid' => $request->ship_id, 'bid' => $id]);

        return redirect()->route('berths.index')->with('success', 'Ship assigned to berth.');
    }

    public function release($id)
    {
        DB::update("
            UPDATE berths
            SET ship_id = NULL, status = 'free', docked_since = NULL
            WHERE berth_id = :id
        ", ['id' => $id]);

        return redirect()->route('berths.index')->with('success', 'Berth released.');
    }
}
