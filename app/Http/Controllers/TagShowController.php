<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Contracts\View\View;
use Spatie\Sheets\Facades\Sheets;

class TagShowController extends Controller
{
    public function __invoke(string $tag): View
    {
        $articles = Sheets::collection('articles')
            ->all()
            ->filter(function (Article $article) use ($tag) {
                return in_array($tag, $article->tags);
            })
            ->paginate(4);

        abort_if($articles->isEmpty(), 404);

        return view('tags.show', [
            'tag' => $tag,
            'articles' => $articles,
        ]);
    }
}
