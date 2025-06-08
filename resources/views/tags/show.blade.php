<x-app-layout>
    <div class="bg-white p-6">
        <x-articles.title>Tag: <span class="text-blue-500">{{ $tag }}</span></x-articles.title>

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-y-5 gap-6 items-start">
            @foreach ($articles as $article)
                <x-articles.list :article="$article" />
            @endforeach
        </div>
        <div class="mt-8">
            {{ $articles->links() }}
        </div>
    </div>
</x-app-layout>
