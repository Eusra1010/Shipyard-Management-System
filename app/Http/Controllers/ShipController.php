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

    public function show($id)
    {
        $ship = DB::selectOne("
            SELECT s.ship_id, s.ship_name, s.ship_type, s.owner_name,
                   s.tonnage, s.flag_country, s.status,
                   TO_CHAR(s.arrival_date, 'DD Mon YYYY') AS arrival_date,
                   NVL(b.berth_name, '—') AS berth_name
            FROM ships s
            LEFT JOIN berths b ON b.ship_id = s.ship_id
            WHERE s.ship_id = :id
        ", ['id' => $id]);

        if (!$ship) abort(404);

        $workOrders = DB::select("
            SELECT order_id, title, status, priority,
                   TO_CHAR(start_date, 'DD Mon YYYY') AS start_date,
                   TO_CHAR(end_date,   'DD Mon YYYY') AS end_date,
                   TO_CHAR(created_at, 'DD Mon YYYY') AS created_at
            FROM work_orders
            WHERE ship_id = :id
            ORDER BY created_at DESC
        ", ['id' => $id]);

        return view('ships.show', compact('ship', 'workOrders'));
    }

    public function create()
    {
        return view('ships.create');
    }

    public function edit($id)
    {
        $ship = DB::selectOne("
            SELECT ship_id, ship_name, ship_type, owner_name, tonnage, flag_country,
                   status, TO_CHAR(arrival_date, 'YYYY-MM-DD') AS arrival_date
            FROM ships WHERE ship_id = ?
        ", [$id]);

        if (!$ship) abort(404);

        return view('ships.edit', compact('ship'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'ship_name'    => 'required|string|max:100',
            'ship_type'    => 'nullable|string|max:50',
            'owner_name'   => 'nullable|string|max:100',
            'tonnage'      => 'nullable|integer|min:1',
            'flag_country' => 'nullable|string|max:50',
            'status'       => 'required|in:docked,in_repair,departed',
            'arrival_date' => 'nullable|date',
        ]);

        DB::update("
            UPDATE ships SET
                ship_name    = ?,
                ship_type    = ?,
                owner_name   = ?,
                tonnage      = ?,
                flag_country = ?,
                status       = ?,
                arrival_date = TO_DATE(?, 'YYYY-MM-DD'),
                updated_at   = SYSDATE
            WHERE ship_id = ?
        ", [
            $data['ship_name'],
            $data['ship_type']    ?? null,
            $data['owner_name']   ?? null,
            $data['tonnage']      ?? null,
            $data['flag_country'] ?? null,
            $data['status'],
            $data['arrival_date'] ?? null,
            $id,
        ]);

        return redirect()->route('ships.index')
                         ->with('success', "\"{$data['ship_name']}\" updated successfully.");
    }

    public function destroy($id)
    {
        $ship = DB::selectOne("SELECT ship_name FROM ships WHERE ship_id = ?", [$id]);
        if (!$ship) abort(404);

        DB::delete("DELETE FROM work_order_workers WHERE order_id IN (SELECT order_id FROM work_orders WHERE ship_id = ?)", [$id]);
        DB::delete("DELETE FROM material_usage     WHERE order_id IN (SELECT order_id FROM work_orders WHERE ship_id = ?)", [$id]);
        DB::delete("DELETE FROM work_orders WHERE ship_id = ?", [$id]);
        DB::update("UPDATE berths SET ship_id = NULL, status = 'free' WHERE ship_id = ?", [$id]);
        DB::delete("DELETE FROM ships WHERE ship_id = ?", [$id]);

        return redirect()->route('ships.index')
                         ->with('success', "\"{$ship->ship_name}\" has been deleted.");
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
