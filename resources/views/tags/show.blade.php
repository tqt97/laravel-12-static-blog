<x-app-layout>
    <x-posts.title>Tag: {{ $tag }}</x-posts.title>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-y-5 gap-6 items-start">
        @foreach ($posts as $post)
            <x-posts.list :post="$post" />
        @endforeach
    </div>
    <div class="mt-8">
        {{ $posts->links() }}
    </div>
</x-app-layout>
