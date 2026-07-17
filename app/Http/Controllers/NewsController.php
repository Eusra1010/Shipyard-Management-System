<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class NewsController extends Controller
{
    public function index()
    {
        $items = DB::select("
            SELECT id, title, description,
                   TO_CHAR(published_at, 'DD/MM/YYYY') AS pub_date,
                   TO_CHAR(published_at, 'YYYY-MM-DD') AS pub_sort,
                   image_path, link, pdf_path
            FROM news
            ORDER BY published_at DESC
        ");

        return view('news.index', compact('items'));
    }
}
