<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaterialController extends Controller
{
    public function index()
    {
        try {
            $materials = DB::select("
                SELECT material_id, name, category, quantity, unit,
                       min_threshold,
                       TO_CHAR(last_restocked, 'DD Mon YYYY') AS last_restocked
                FROM materials
                ORDER BY category, name
            ");

            $total = count($materials);
        } catch (\Exception $e) {
            $materials = [];
            $total     = 0;
        }

        return view('materials.index', compact('materials', 'total'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|max:100',
            'category'      => 'required|max:50',
            'quantity'      => 'required|integer|min:0',
            'unit'          => 'required|max:20',
            'min_threshold' => 'required|integer|min:0',
        ]);

        $newId = DB::selectOne("SELECT NVL(MAX(material_id),0)+1 AS nxt FROM materials")->nxt;

        DB::insert("
            INSERT INTO materials (material_id, name, category, quantity, unit, min_threshold, last_restocked)
            VALUES (:id, :name, :cat, :qty, :unit, :thr, SYSDATE)
        ", [
            'id'   => $newId,
            'name' => $request->name,
            'cat'  => $request->category,
            'qty'  => (int) $request->quantity,
            'unit' => $request->unit,
            'thr'  => (int) $request->min_threshold,
        ]);

        return redirect()->route('materials.index')->with('success', 'Material added.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|max:100',
            'category'      => 'required|max:50',
            'quantity'      => 'required|integer|min:0',
            'unit'          => 'required|max:20',
            'min_threshold' => 'required|integer|min:0',
        ]);

        DB::update("
            UPDATE materials
            SET name = :name, category = :cat, quantity = :qty,
                unit = :unit, min_threshold = :thr
            WHERE material_id = :id
        ", [
            'name' => $request->name,
            'cat'  => $request->category,
            'qty'  => (int) $request->quantity,
            'unit' => $request->unit,
            'thr'  => (int) $request->min_threshold,
            'id'   => $id,
        ]);

        return redirect()->route('materials.index')->with('success', 'Material updated.');
    }

    public function restock(Request $request, $id)
    {
        $request->validate([
            'add_qty' => 'required|integer|min:1',
        ]);

        DB::update("
            UPDATE materials
            SET quantity = quantity + :qty,
                last_restocked = SYSDATE
            WHERE material_id = :id
        ", ['qty' => (int) $request->add_qty, 'id' => $id]);

        return redirect()->route('materials.index')->with('success', 'Stock updated.');
    }

    public function destroy($id)
    {
        DB::delete("DELETE FROM materials WHERE material_id = :id", ['id' => $id]);
        return redirect()->route('materials.index')->with('success', 'Material removed.');
    }
}
