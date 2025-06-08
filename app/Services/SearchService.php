<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SearchService
{
    public function search(string $query, Collection $articles): Collection
    {
        logger()->debug('Search initiated', ['query' => $query]);

        $cacheKey = $this->getCacheKey($query);

        $query = $this->normalizeQuery($query);
        if (empty($query)) {
            logger()->debug('Empty search query');

            return collect();
        }

        $results = Cache::remember($cacheKey, 300, function () use ($articles, $query) {
            $index = Cache::get('search_index', []);

            if (empty($index)) {
                logger()->error('Search index empty');

                return collect();
            }

            return collect($index)
                ->mapWithKeys(fn ($v, $k) => [
                    $k => $this->calculateScore($v, $query),
                ])
                ->filter(fn ($score) => $score > 0)
                ->sortDesc()
                ->keys()
                ->map(fn ($slug) => $articles->firstWhere('slug', $slug))
                ->filter();
        });

        logger()->debug('Search results', [
            'cache_key' => $cacheKey,
            'results_count' => $results->count(),
        ]);

        return $results;
    }

    private function getCacheKey(string $query): string
    {
        $locale = app()->getLocale();

        return config('app.name').'_search_'.md5($locale.'_'.Str::lower($query));
    }

    private function normalizeQuery(string $query): array
    {
        return Str::of($query)
            ->squish()
            ->lower()
            ->trim()
            ->matchAll('/("[^"]*"|\S+)/') // Regex đã sửa
            ->map(fn ($t) => trim($t, '"'))
            ->reject(fn ($t) => empty($t))
            ->toArray();
    }

    private function calculateScore(array $index, array $queryTerms): float
    {
        $score = 0;
        $content = collect($index)
            ->except('slug')
            ->map(fn ($v) => is_array($v) ? implode(' ', $v) : $v)
            ->implode(' ');

        foreach ($queryTerms as $term) {
            $termType = $this->detectTermType($term);

            switch ($termType['type']) {
                case 'exact':
                    $exactMatch = Str::contains($index['t'], $termType['value'])
                        || Str::contains($content, $termType['value']);
                    if ($exactMatch) {
                        $score += 5;
                    }
                    break;

                case 'required':
                    if (! Str::contains($content, $termType['value'])) {
                        return 0;
                    }
                    $score += 3;
                    break;

                case 'optional':
                    if (Str::contains($content, $termType['value'])) {
                        $score += 1;
                    }
                    break;
            }
        }

        return $score;
    }

    private function detectTermType(string $term): array
    {
        if (Str::startsWith($term, '+')) {
            return [
                'type' => 'required',
                'value' => Str::after($term, '+'),
            ];
        }

        if (Str::startsWith($term, '"') && Str::endsWith($term, '"')) {
            return [
                'type' => 'exact',
                'value' => trim($term, '"'),
            ];
        }

        return [
            'type' => 'optional',
            'value' => $term,
        ];
    }
}
