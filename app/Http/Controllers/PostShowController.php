<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Contracts\View\View;

class PostShowController extends Controller
{
    public function __invoke(Post $post): View
    {
        return view('posts.show', [
            'post' => $post,
        ]);
    }
}
