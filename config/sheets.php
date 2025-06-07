<?php

use App\ContentParsers\MarkdownWithFrontMatterParser;

return [
    'default_collection' => 'articles',

    'collections' => [

        'articles' => [
            'disk' => 'articles',
            'sheet_class' => App\Models\Article::class,
            'path_parser' => Spatie\Sheets\PathParsers\SlugWithDateParser::class,
            'content_parser' => MarkdownWithFrontMatterParser::class,
            'extension' => 'md',
        ],
    ],
];
