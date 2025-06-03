<x-app-layout>
    <div class="space-y-16">
        @forelse ($posts as $post)
            <x-posts.list-item :post="$post" />
        @empty
            <p>Nothing here yet.</p>
        @endforelse
    </div>
    <div class="mt-2">
        {{ $posts->links() }}
    </div>
</x-app-layout>
