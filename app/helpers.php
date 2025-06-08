<?php

use App\Enums\SortOrder;

if (! function_exists('sortOrder')) {
    function sortOrder(string $key): ?string
    {
        return match (strtolower($key)) {
            'asc' => SortOrder::ASC->value,
            'desc' => SortOrder::DESC->value,
            default => null,
        };
    }
}

if (! function_exists('sortOrderDefault')) {
    function sortOrderDefault(): string
    {
        return SortOrder::default();
    }
}

if (! function_exists('sortOrderDesc')) {
    function sortOrderDesc(): string
    {
        return SortOrder::DESC->value;
    }
}

if (! function_exists('sortOrderAsc')) {
    function sortOrderAsc(): string
    {
        return SortOrder::ASC->value;
    }
}

if (! function_exists('parseSortOrder')) {
    function parseSortOrder(?string $value): SortOrder
    {
        return SortOrder::parse(strtolower($value ?? '')) ?? SortOrder::from(SortOrder::default());
    }
}

if (! function_exists('highlight')) {
    function highlight(string $text, ?string $query): string
    {
        $text = e($text);
        $query = e($query);

        if (empty($query)) {
            return $text;
        }
        $query = preg_quote($query, '/');

        return preg_replace('/('.$query.')/i', '<mark>$1</mark>', $text);
    }
}
