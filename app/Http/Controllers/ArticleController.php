<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Sheet;

class ArticleController extends Controller
{
    public function show(Article $article): View
    {
        $articles = Sheets::collection('articles')->all()->sortByDesc('date')->values();

        $currentIndex = $articles->search(fn (Sheet $sheet) => $sheet->slug === $article->slug);

        $current = $articles[$currentIndex] ?? abort(404);
        $previous = $articles[$currentIndex + 1] ?? null;
        $next = $articles[$currentIndex - 1] ?? null;

        return view('articles.show', [
            'article' => $current,
            'previous' => $previous,
            'next' => $next,
        ]);
    }
}
