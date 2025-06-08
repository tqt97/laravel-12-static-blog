<?php

use App\Models\Article;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Sheet;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('search:build-index', function () {
    $articles = Sheets::collection(Article::SHEETS_COLLECTION_KEY)->all();

    $searchIndex = $articles->map(function (Sheet $article) {
        return [
            'slug' => $article->slug,
            'title' => strtolower($article->title),
            'content' => strtolower(strip_tags((string) $article->content)),
        ];
    });

    $path = storage_path('app/search_index.json');

    File::put($path, $searchIndex->toJson(JSON_PRETTY_PRINT));

    $this->info('Search index built: '.$path);
});
