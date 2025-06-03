<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PostIndexController extends Controller
{
    public function __invoke(): View
    {
        return view('posts.index');
    }
}
