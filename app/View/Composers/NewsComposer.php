<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class NewsComposer
{
    public function compose(View $view): void
    {
        try {
            $latestNews = DB::select("
                SELECT id, title,
                       TO_CHAR(published_at, 'DD Mon YYYY') AS published_at,
                       image_path
                FROM (
                    SELECT id, title, published_at, image_path
                    FROM news
                    ORDER BY published_at DESC
                )
                WHERE ROWNUM <= 5
            ");
        } catch (\Exception $e) {
            $latestNews = [];
        }

        $view->with('latestNews', $latestNews);
    }
}
