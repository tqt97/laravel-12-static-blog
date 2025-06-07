<x-app-layout>
    <x-articles.title>Latest article</x-articles.title>

    <ul class="grid grid-cols-1 xl:grid-cols-2 gap-y-5 gap-6 items-start">
        @forelse ($articles as $article)
            <x-articles.list :article="$article" />
        @empty
            <p>Nothing here yet.</p>
        @endforelse
    </ul>
    <div class="mt-8">
        {{ $articles->links() }}
    </div>
</x-app-layout>
