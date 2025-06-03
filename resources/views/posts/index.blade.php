<x-app-layout>
    <div class="space-y-16">
        @forelse ($posts as $post)
            <article class="prose">
                <h1 class="not-prose">
                    <a href="{{ route('posts.show', $post->slug) }}"
                        class="text-2xl font-bold hover:text-blue-500 transition-colors duration-100">{{ $post->title }}</a>
                </h1>
                <div>{{ $post->teaser }}</div>
                <x-post-meta :post="$post" />
            </article>
        @empty
            <p>Nothing here yet.</p>
        @endforelse
    </div>
</x-app-layout>
