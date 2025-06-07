<x-app-layout>
    <x-posts.title>Latest posts</x-posts.title>

    <ul class="grid grid-cols-1 xl:grid-cols-2 gap-y-5 gap-6 items-start">
        @forelse ($posts as $post)
            <x-posts.list :post="$post" />
        @empty
            <p>Nothing here yet.</p>
        @endforelse
    </ul>
    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</x-app-layout>
