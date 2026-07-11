<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipController extends Controller
{
    public function index()
    {
        // get_active_jobs() is an Oracle function defined in database/sql/plsql_objects.sql
        $ships = DB::select("
            SELECT s.ship_id, s.ship_name, s.ship_type, s.owner_name,
                   s.tonnage, s.flag_country, s.status, s.arrival_date,
                   b.berth_name,
                   get_active_jobs(s.ship_id) AS active_jobs
            FROM ships s
            LEFT JOIN berths b ON b.ship_id = s.ship_id
            ORDER BY s.arrival_date DESC
        ");

        return view('ships.index', compact('ships'));
    }

    public function create()
    {
        return view('ships.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ship_name'   => 'required|string|max:100',
            'ship_type'   => 'nullable|string|max:50',
            'owner_name'  => 'nullable|string|max:100',
            'tonnage'     => 'nullable|integer|min:1',
            'flag_country'=> 'nullable|string|max:50',
            'status'      => 'required|in:docked,in_repair,departed',
            'arrival_date'=> 'nullable|date',
        ]);

        
        $row = DB::selectOne("SELECT NVL(MAX(ship_id), 0) + 1 AS next_id FROM ships");
        $nextId = $row->next_id;

        DB::insert("
            INSERT INTO ships
                (ship_id, ship_name, ship_type, owner_name, tonnage, flag_country, status, arrival_date, created_at, updated_at)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?, SYSDATE, SYSDATE)
        ", [
            $nextId,
            $data['ship_name'],
            $data['ship_type']    ?? null,
            $data['owner_name']   ?? null,
            $data['tonnage']      ?? null,
            $data['flag_country'] ?? null,
            $data['status'],
            $data['arrival_date'] ?? null,
        ]);

        return redirect()->route('ships.index')
                         ->with('success', "Ship \"{$data['ship_name']}\" registered successfully.");
    }
}
