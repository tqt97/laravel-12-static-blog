<x-app-layout>
    <x-articles.title>Tag: {{ $tag }}</x-articles.title>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-y-5 gap-6 items-start">
        @foreach ($articles as $article)
            <x-articles.list :article="$article" />
        @endforeach
    </div>
    <div class="mt-8">
        {{ $articles->links() }}
    </div>
</x-app-layout>
