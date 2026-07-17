<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class FacilityController extends Controller
{
    public function index()
    {
        try {
            $dryDock = DB::selectOne("
                SELECT s.ship_name
                FROM berths b
                JOIN ships s ON s.ship_id = b.ship_id
                WHERE b.berth_type = 'Dry Dock'
                AND b.status = 'occupied'
                AND ROWNUM = 1
            ");
        } catch (\Exception $e) {
            $dryDock = null;
        }

        return view('facility', ['dryDockShip' => $dryDock?->ship_name ?? null]);
    }
}
