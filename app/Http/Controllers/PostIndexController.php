<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Spatie\Sheets\Facades\Sheets;

class PostIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('posts.index', [
            'posts' => Sheets::collection('posts')->all()->sortByDesc('date'),
        ]);
    }
}
