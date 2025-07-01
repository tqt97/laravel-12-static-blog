<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SortOrder;
use App\Http\Requests\HomeRequest;
use App\Models\Article;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Sheet;

class HomeController extends Controller
{
    const SORT_DATE_KEY = 'date';

    const ARTICLES_PER_PAGE = 4;

    /**
     * Show the home page, filtered by search and sorted by sort order.
     */
    public function __invoke(HomeRequest $request): View
    {
        $articles = $this->getCachedArticles();
        $articles = $this->applySort($articles, parseSortOrder($request->input('sort'))); // sort first
        $articles = $this->applySearch($articles, $request->input('search')); //  search after
        $paginatedArticles = $this->paginateCollection($articles, self::ARTICLES_PER_PAGE);

        return view('home', [
            'articles' => $paginatedArticles,
        ]);
    }

    /**
     * Paginate a collection of items.
     *
     * @param  Collection<int, mixed>  $items
     * @return LengthAwarePaginator<int, mixed>
     */
    private function paginateCollection(Collection $items, int $perPage): LengthAwarePaginator
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $path = LengthAwarePaginator::resolveCurrentPath();

        return new LengthAwarePaginator(
            $items->slice(($currentPage - 1) * $perPage, $perPage),
            $items->count(),
            $perPage,
            $currentPage,
            [
                'path' => $path,
                'query' => request()->query(),
            ]
        );
    }

    /**
     * Filter the given collection of articles by the given search term.
     *
     * If the search term is empty, the collection is returned as is.
     *
     * @param  Collection<int, mixed>  $articles
     * @return Collection<int, mixed>
     */
    private function applySearch(Collection $articles, ?string $search): Collection
    {
        if (empty($search)) {
            return $articles;
        }

        $cacheKey = 'search_'.md5($search).'_'.app()->getLocale();

        return Cache::remember($cacheKey, 600, function () use ($articles, $search) {
            return $articles->filter(function (Sheet $article) use ($search) {
                $searchableContent = Str::lower(
                    $article->title
                        // . ' ' . $article->contents
                        // . ' ' . ($article->author ?? '')
                        .' '.implode(' ', (array) $article->tags ?? [])
                );

                // single-word search
                // return Str::contains($searchableContent, $search);
                // multi-word search
                return collect(explode(' ', $search))
                    ->every(fn ($term) => Str::contains($searchableContent, $term));
            });
        });
    }

    /**
     * Sort the given collection of articles by the given sort order.
     *
     * The collection is sorted by the 'date' attribute of each article.
     *
     * @param  Collection<int, mixed>  $articles
     * @return Collection<int, mixed>
     */
    private function applySort(Collection $articles, SortOrder $sortOrder): Collection
    {
        $sortOrder = $sortOrder ?? SortOrder::default();

        if ($sortOrder === SortOrder::default()) {
            return $articles;
        }

        return match ($sortOrder) {
            SortOrder::DESC => $articles->sortByDesc(self::SORT_DATE_KEY),
            SortOrder::ASC => $articles->sortBy(self::SORT_DATE_KEY),
        };
    }

    /**
     * Returns a cached collection of articles.
     *
     * The articles are fetched from the 'articles' sheet collection.
     * The collection is cached for 1 hour and 1 day.
     * When the cache is invalidated, the 'search_index' cache is also updated.
     *
     * @return Collection<int, Article>
     */
    private function getCachedArticles(): Collection
    {
        return Cache::flexible(
            key: Article::ARTICLES_CACHE_KEY,
            ttl: [now()->addHour(), now()->addDay()],
            callback: function () {
                $articles = Sheets::collection(Article::SHEETS_COLLECTION_KEY)->all();

                $searchIndex = $articles->mapWithKeys(function ($article) {
                    return [$article->slug => $this->buildSearchIndex($article)];
                });

                Cache::put('search_index', $searchIndex);

                return $articles;
            }
        );
    }

    /**
     * Builds a search index array for a given article.
     *
     * The index includes the lowercase title, contents, author, and tags,
     * as well as the article's slug.
     *
     * @param  Sheet  $article  The article to be indexed.
     * @return array The search index array containing the article's searchable fields.
     */
    private function buildSearchIndex(Sheet $article): array
    {
        return [
            't' => Str::lower($article->title),
            // 'c' => Str::lower($article->contents),
            // 'a' => Str::lower($article->author ?? ''),
            'g' => array_map('Str::lower', (array) $article->tags ?? []),
            'slug' => $article->slug,
        ];
    }
}
