<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\SortOrder;
use App\Http\Requests\HomeRequest;
use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Spatie\Sheets\Facades\Sheets;
use Spatie\Sheets\Sheet;

class HomeController extends Controller
{
    const ARTICLES_CACHE_KEY = 'articles';

    const SORT_DATE_KEY = 'date';

    const ARTICLES_PER_PAGE = 4;

    const SHEETS_COLLECTION_KEY = 'articles';

    /**
     * Show the home page, filtered by search and sorted by sort order.
     */
    public function __invoke(HomeRequest $request): View
    {
        $articles = $this->getCachedArticles();
        $articles = $this->applySearch($articles, $request->input('search'));
        $articles = $this->applySort($articles, parseSortOrder($request->input('sort')));

        // Paginate (convert Collection to LengthAwarePaginator)
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
        $itemsPaged = $items->forPage($currentPage, $perPage)->values();

        return new LengthAwarePaginator(
            $itemsPaged,
            $items->count(),
            $perPage,
            $currentPage,
            [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
                'query' => request()->query(),
            ]
        );
    }

    /**
     * Retrieve articles from cache or fetch from the source if not cached.
     *
     * @return Collection<int, mixed> The collection of articles.
     */
    private function getCachedArticles(): Collection
    {
        return Cache::flexible(
            key: self::ARTICLES_CACHE_KEY,
            ttl: [now()->addHour(), now()->addDay()],
            callback: fn () => Sheets::collection(self::SHEETS_COLLECTION_KEY)->all(),
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
        $search = strtolower(trim($search ?? ''));

        if (empty($search)) {
            return $articles;
        }

        return empty($search)
            ? $articles
            : $articles->filter(fn (Sheet $article) => str_contains(strtolower($article->title), $search));
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
}
