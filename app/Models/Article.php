<?php

namespace App\Models;

use Illuminate\Support\Str;
use Spatie\Sheets\Sheet;

class Article extends Sheet
{
    const SHEETS_COLLECTION_KEY = 'articles';

    const ARTICLES_CACHE_KEY = 'articles';

    public function getExcerptAttribute(): string
    {
        return Str::limit($this->contents, 150);
    }
}
