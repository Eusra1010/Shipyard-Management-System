<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class SupervisorController extends Controller
{
    private function supervisor()
    {
        return auth()->user();
    }

    private function team(): string
    {
        return $this->supervisor()->team ?? '';
    }

    public function dashboard()
    {
        $user = $this->supervisor();
        $team = $this->team();

        // Crew = other users on same team
        $crew = DB::select(
            "SELECT id, name, email, status, worker_id FROM users
             WHERE team = ? AND id != ? AND role != 'admin'
             ORDER BY name",
            [$team, $user->id]
        );

        // All user IDs on this team (including supervisor)
        $teamUserIds = array_merge([$user->id], array_column($crew, 'id'));

        // All worker_ids linked to team users (for job lookup)
        $workerIds = array_filter(
            array_merge(
                [$user->worker_id ?? null],
                array_column($crew, 'worker_id')
            )
        );

        $jobs = [];
        if (count($workerIds)) {
            $placeholders = implode(',', array_fill(0, count($workerIds), '?'));
            $jobs = DB::select("
                SELECT order_id, title, status, priority,
                       start_date, end_date, overdue, ship_name, berth_name
                FROM (
                    SELECT DISTINCT
                        wo.order_id, wo.title, wo.status, wo.priority,
                        TO_CHAR(wo.start_date, 'DD Mon YYYY') AS start_date,
                        TO_CHAR(wo.end_date,   'DD Mon YYYY') AS end_date,
                        CASE WHEN wo.end_date < SYSDATE AND wo.status != 'done' THEN 1 ELSE 0 END AS overdue,
                        s.ship_name, NVL(b.berth_name, '-') AS berth_name,
                        wo.start_date AS sort_date, wo.status AS sort_status
                    FROM work_order_workers ww
                    JOIN work_orders wo ON wo.order_id = ww.order_id
                    JOIN ships s        ON s.ship_id   = wo.ship_id
                    LEFT JOIN berths b  ON b.ship_id   = s.ship_id
                    WHERE ww.worker_id IN ($placeholders)
                )
                ORDER BY sort_status ASC, sort_date DESC
            ", $workerIds);
        }

        // Stats
        $assignedJobs = count(array_filter($jobs, fn($j) => $j->status !== 'done'));
        $crewOnDuty   = count(array_filter($crew, fn($c) => $c->status === 'busy'));
        $totalCrew    = count($crew);
        $dueToday     = 0;
        if (count($workerIds)) {
            $placeholders = implode(',', array_fill(0, count($workerIds), '?'));
            $dueToday = (int) DB::selectOne("
                SELECT COUNT(DISTINCT wo.order_id) AS cnt
                FROM work_order_workers ww
                JOIN work_orders wo ON wo.order_id = ww.order_id
                WHERE ww.worker_id IN ($placeholders)
                AND TRUNC(wo.end_date) = TRUNC(SYSDATE)
                AND wo.status != 'done'
            ", $workerIds)->cnt;
        }

        return view('supervisor.dashboard', compact(
            'user', 'crew', 'jobs', 'team',
            'assignedJobs', 'crewOnDuty', 'totalCrew', 'dueToday'
        ));
    }

    public function crew()
    {
        $user = $this->supervisor();
        $team = $this->team();

        $crew = DB::select(
            "SELECT u.id, u.name, u.email, u.status, u.worker_id
             FROM users u
             WHERE u.team = ? AND u.id != ? AND u.role != 'admin'
             ORDER BY u.name",
            [$team, $user->id]
        );

        // For each crew member, fetch their current active job
        foreach ($crew as $c) {
            $c->active_job = null;
            if ($c->worker_id) {
                $c->active_job = DB::selectOne("
                    SELECT * FROM (
                        SELECT wo.title, s.ship_name, wo.status
                        FROM work_order_workers ww
                        JOIN work_orders wo ON wo.order_id = ww.order_id
                        JOIN ships s        ON s.ship_id   = wo.ship_id
                        WHERE ww.worker_id = ? AND wo.status != 'done'
                        ORDER BY wo.created_at DESC
                    ) WHERE ROWNUM = 1
                ", [$c->worker_id]);
            }
        }

        return view('supervisor.crew', compact('user', 'crew', 'team'));
    }

    public function profile()
    {
        $user = $this->supervisor();
        $team = $this->team();

        $worker = null;
        $history = [];
        $stats   = (object)['completed' => 0, 'active' => 0];

        if ($user->worker_id) {
            $worker = DB::selectOne(
                "SELECT worker_id, name, role, phone, status FROM workers WHERE worker_id = ?",
                [$user->worker_id]
            );

            $history = DB::select("
                SELECT * FROM (
                    SELECT wo.order_id, wo.title, wo.status,
                           TO_CHAR(wo.start_date, 'DD Mon YYYY') AS start_date,
                           TO_CHAR(wo.end_date,   'DD Mon YYYY') AS end_date,
                           s.ship_name
                    FROM work_order_workers ww
                    JOIN work_orders wo ON wo.order_id = ww.order_id
                    JOIN ships s        ON s.ship_id   = wo.ship_id
                    WHERE ww.worker_id = ?
                    ORDER BY wo.start_date DESC
                ) WHERE ROWNUM <= 20
            ", [$user->worker_id]);

            $stats = DB::selectOne("
                SELECT
                    SUM(CASE WHEN wo.status = 'done'  THEN 1 ELSE 0 END) AS completed,
                    SUM(CASE WHEN wo.status != 'done' THEN 1 ELSE 0 END) AS active
                FROM work_order_workers ww
                JOIN work_orders wo ON wo.order_id = ww.order_id
                WHERE ww.worker_id = ?
            ", [$user->worker_id]) ?? $stats;
        }

        return view('supervisor.profile', compact('user', 'team', 'worker', 'history', 'stats'));
    }
}
