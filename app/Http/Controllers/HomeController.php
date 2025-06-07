<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Spatie\Sheets\Facades\Sheets;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $articles = Cache::flexible(
            key: 'articles',
            ttl: [now()->addHour(), now()->addDay()],
            callback: fn () => Sheets::collection('articles')->all()->sortByDesc('date'),
        );

        $paginatedArticles = $articles->paginate(4);

        return view('home', [
            'articles' => $paginatedArticles,
        ]);
    }
}
