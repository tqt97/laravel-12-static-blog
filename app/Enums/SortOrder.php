<?php

namespace App\Enums;

enum SortOrder: string
{
    case ASC = 'asc';
    case DESC = 'desc';

    public static function all(): array
    {
        return [
            self::ASC->value,
            self::DESC->value,
        ];
    }

    public static function default(): string
    {
        return self::DESC->value;
    }

    public static function parse(string $value): ?self
    {
        return match ($value) {
            self::ASC->value => self::ASC,
            self::DESC->value => self::DESC,
            default => null,
        };
    }
}
