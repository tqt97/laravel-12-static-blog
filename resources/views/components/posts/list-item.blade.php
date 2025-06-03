<article class="prose">
    <h1 class="not-prose">
        <a href="{{ route('posts.show', $post->slug) }}"
            class="text-xl font-bold hover:text-blue-500 transition-colors duration-100">{{ $post->title }}</a>
    </h1>
    <div>{{ $post->teaser }}</div>
    <x-posts.meta :post="$post" />
</article>
