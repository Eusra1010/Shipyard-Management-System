<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users   = DB::select("SELECT id, name, email, role, worker_id FROM users ORDER BY name");
        $workers = DB::select("SELECT worker_id, name, role FROM workers ORDER BY name");

        return view('admin.users.index', compact('users', 'workers'));
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate(['role' => ['required', 'in:supervisor,admin']]);

        $user = DB::selectOne("SELECT name FROM users WHERE id = ?", [$id]);
        if (!$user) abort(404);

        DB::update("UPDATE users SET role = ? WHERE id = ?", [$request->role, $id]);

        return back()->with('status', "Updated {$user->name}'s role to {$request->role}.");
    }

    public function linkTeam(Request $request, $id)
    {
        $team = trim($request->input('team') ?? '');
        $user = DB::selectOne("SELECT name FROM users WHERE id = ?", [$id]);
        if (!$user) abort(404);

        DB::update("UPDATE users SET team = ? WHERE id = ?", [$team ?: null, $id]);

        return back()->with('status', "Team updated for {$user->name}.");
    }

    public function linkWorker(Request $request, $id)
    {
        $workerId = $request->input('worker_id') ?: null;

        $user = DB::selectOne("SELECT name FROM users WHERE id = ?", [$id]);
        if (!$user) abort(404);

        DB::update("UPDATE users SET worker_id = ? WHERE id = ?", [$workerId, $id]);

        $msg = $workerId
            ? "Linked {$user->name} to a worker profile."
            : "Removed worker link for {$user->name}.";

        return back()->with('status', $msg);
    }
}
