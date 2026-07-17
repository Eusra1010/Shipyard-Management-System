<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsAdminController extends Controller
{
    public function index()
    {
        $items = DB::select("
            SELECT id, title, TO_CHAR(published_at, 'DD Mon YYYY') AS pub_date, link, pdf_path
            FROM news
            ORDER BY published_at DESC
        ");

        return view('admin.news.index', compact('items'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|max:200',
            'description'  => 'required|max:2000',
            'published_at' => 'required|date',
        ]);

        $row = DB::selectOne("SELECT NVL(MAX(id),0)+1 AS next_id FROM news");

        DB::insert("
            INSERT INTO news (id, title, description, published_at, image_path, link, pdf_path, created_at, updated_at)
            VALUES (:id, :title, :description, TO_DATE(:pub, 'YYYY-MM-DD'), :image_path, :link, :pdf_path, SYSDATE, SYSDATE)
        ", [
            'id'          => $row->next_id,
            'title'       => $request->title,
            'description' => $request->description,
            'pub'         => $request->published_at,
            'image_path'  => $request->image_path ?: null,
            'link'        => $request->link ?: null,
            'pdf_path'    => $request->pdf_path ?: null,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News item published.');
    }

    public function edit($id)
    {
        $item = DB::selectOne("
            SELECT id, title, description,
                   TO_CHAR(published_at, 'YYYY-MM-DD') AS published_at,
                   image_path, link, pdf_path
            FROM news WHERE id = :id
        ", ['id' => $id]);

        if (!$item) abort(404);

        return view('admin.news.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'        => 'required|max:200',
            'description'  => 'required|max:2000',
            'published_at' => 'required|date',
        ]);

        DB::update("
            UPDATE news
            SET title = :title,
                description = :description,
                published_at = TO_DATE(:pub, 'YYYY-MM-DD'),
                image_path = :image_path,
                link = :link,
                pdf_path = :pdf_path,
                updated_at = SYSDATE
            WHERE id = :id
        ", [
            'title'       => $request->title,
            'description' => $request->description,
            'pub'         => $request->published_at,
            'image_path'  => $request->image_path ?: null,
            'link'        => $request->link ?: null,
            'pdf_path'    => $request->pdf_path ?: null,
            'id'          => $id,
        ]);

        return redirect()->route('admin.news.index')->with('success', 'News item updated.');
    }

    public function destroy($id)
    {
        DB::delete("DELETE FROM news WHERE id = :id", ['id' => $id]);

        return redirect()->route('admin.news.index')->with('success', 'News item deleted.');
    }
}
