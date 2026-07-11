<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{
    public function create()
    {
        $ships = DB::select("
            SELECT ship_id, ship_name FROM ships
            WHERE status != 'departed'
            ORDER BY ship_name
        ");
        return view('work-orders.create', compact('ships'));
    }

    // Calls the Oracle stored procedure create_work_order via PDO.
    public function store(Request $request)
    {
        $request->validate([
            'ship_id'     => 'required|integer',
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
        ]);

        $pdo    = DB::getPdo();
        $result = '';

        $stmt = $pdo->prepare(
            "BEGIN create_work_order(:p_ship_id, :p_title, :p_desc,
                TO_DATE(:p_start, 'YYYY-MM-DD'), TO_DATE(:p_end, 'YYYY-MM-DD'),
                :p_result); END;"
        );

        $shipId = (int) $request->ship_id;
        $title  = $request->title;
        $desc   = $request->description ?? '';
        $start  = $request->start_date;
        $end    = $request->end_date;

        $stmt->bindParam(':p_ship_id', $shipId, \PDO::PARAM_INT);
        $stmt->bindParam(':p_title',   $title,  \PDO::PARAM_STR);
        $stmt->bindParam(':p_desc',    $desc,   \PDO::PARAM_STR);
        $stmt->bindParam(':p_start',   $start,  \PDO::PARAM_STR);
        $stmt->bindParam(':p_end',     $end,    \PDO::PARAM_STR);
        $stmt->bindParam(':p_result',  $result, \PDO::PARAM_STR | \PDO::PARAM_INPUT_OUTPUT, 200);

        $stmt->execute();

        if (str_starts_with($result, 'ERROR')) {
            return back()->withInput()->withErrors(['oracle' => $result]);
        }

        return redirect()->route('dashboard')->with('success', 'Work order created successfully.');
    }
}
