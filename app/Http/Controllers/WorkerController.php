<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkerController extends Controller
{
    public function index()
    {
        try {
            $workers = DB::select("
                SELECT worker_id, name, role, phone, status
                FROM workers
                ORDER BY role, name
            ");

            $assignments = DB::select("
                SELECT ww.worker_id, s.ship_name, wo.title AS job_title
                FROM work_order_workers ww
                JOIN work_orders wo ON wo.order_id = ww.order_id
                JOIN ships s ON s.ship_id = wo.ship_id
                WHERE wo.status != 'done'
            ");

            $statsRows = DB::select("
                SELECT ww.worker_id,
                       SUM(CASE WHEN wo.status = 'done' THEN 1 ELSE 0 END) AS completed,
                       SUM(CASE WHEN wo.status != 'done' THEN 1 ELSE 0 END) AS active
                FROM work_order_workers ww
                JOIN work_orders wo ON wo.order_id = ww.order_id
                GROUP BY ww.worker_id
            ");

            $historyRows = DB::select("
                SELECT ww.worker_id, wo.title, wo.status,
                       TO_CHAR(wo.start_date, 'DD Mon YYYY') AS start_date,
                       TO_CHAR(wo.end_date,   'DD Mon YYYY') AS end_date,
                       s.ship_name
                FROM work_order_workers ww
                JOIN work_orders wo ON wo.order_id = ww.order_id
                JOIN ships s ON s.ship_id = wo.ship_id
                ORDER BY ww.worker_id, wo.start_date DESC
            ");

        } catch (\Exception $e) {
            $workers = $assignments = $statsRows = $historyRows = [];
        }

        $assignMap = [];
        foreach ($assignments as $a) {
            if (!isset($assignMap[$a->worker_id])) $assignMap[$a->worker_id] = $a;
        }

        $statsMap = [];
        foreach ($statsRows as $s) {
            $statsMap[$s->worker_id] = $s;
        }

        $historyMap = [];
        foreach ($historyRows as $h) {
            $historyMap[$h->worker_id][] = [
                'title'  => $h->title,
                'status' => $h->status,
                'start'  => $h->start_date,
                'end'    => $h->end_date,
                'ship'   => $h->ship_name,
            ];
        }

        $jsData = [];
        foreach ($workers as $w) {
            $words    = preg_split('/\s+/', trim($w->name));
            $initials = strtoupper(implode('', array_map(fn($word) => $word[0] ?? '', $words)));
            $initials = substr($initials, 0, 2);

            $a = $assignMap[$w->worker_id] ?? null;
            $s = $statsMap[$w->worker_id]  ?? null;

            $jsData[$w->worker_id] = [
                'id'        => $w->worker_id,
                'name'      => $w->name,
                'role'      => $w->role ?? 'Unknown',
                'phone'     => $w->phone ?? '—',
                'status'    => $w->status,
                'initials'  => $initials,
                'ship'      => $a ? $a->ship_name  : null,
                'job'       => $a ? $a->job_title  : null,
                'completed' => $s ? (int) $s->completed : 0,
                'active'    => $s ? (int) $s->active    : 0,
                'history'   => $historyMap[$w->worker_id] ?? [],
            ];
        }

        return view('workers.index', compact('workers', 'jsData'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'role'  => 'nullable|string|max:50',
            'phone' => 'nullable|string|max:20',
        ]);

        $row = DB::selectOne("SELECT NVL(MAX(worker_id), 0) + 1 AS next_id FROM workers");
        DB::insert(
            "INSERT INTO workers (worker_id, name, role, phone, status) VALUES (?, ?, ?, ?, 'available')",
            [$row->next_id, $data['name'], $data['role'] ?? null, $data['phone'] ?? null]
        );

        return redirect()->route('workers.index')
                         ->with('success', "\"{$data['name']}\" added to the workforce.");
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name'   => 'required|string|max:100',
            'role'   => 'nullable|string|max:50',
            'phone'  => 'nullable|string|max:20',
            'status' => 'required|in:available,busy',
        ]);

        DB::update(
            "UPDATE workers SET name = ?, role = ?, phone = ?, status = ? WHERE worker_id = ?",
            [$data['name'], $data['role'] ?? null, $data['phone'] ?? null, $data['status'], $id]
        );

        return redirect()->route('workers.index')
                         ->with('success', "Worker updated.");
    }

    public function destroy($id)
    {
        $worker = DB::selectOne("SELECT name FROM workers WHERE worker_id = ?", [$id]);
        if (!$worker) abort(404);

        DB::delete("DELETE FROM work_order_workers WHERE worker_id = ?", [$id]);
        DB::delete("DELETE FROM workers WHERE worker_id = ?", [$id]);

        return redirect()->route('workers.index')
                         ->with('success', "\"{$worker->name}\" removed.");
    }
}
