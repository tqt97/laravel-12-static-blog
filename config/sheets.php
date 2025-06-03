<?php

use App\ContentParsers\MarkdownWithFrontMatterParser;

return [
    'default_collection' => 'posts',

    'collections' => [

        'posts' => [
            'disk' => 'posts',
            'sheet_class' => App\Models\Post::class,
            'path_parser' => Spatie\Sheets\PathParsers\SlugWithDateParser::class,
            'content_parser' => MarkdownWithFrontMatterParser::class,
            'extension' => 'md',
        ],
    ],
];
