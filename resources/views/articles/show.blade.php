<x-app-layout>
    <x-slot name="toc">
        <aside
            class="max-w-[300px] min-w-[200px] hidden lg:block fixed right-24 top-1/4 max-h-96 overflow-y-auto bg-white border border-gray-200 shadow rounded p-4">
            <h2 class="text-base font-semibold mb-3 border-b">Table of contents</h2>
            <ul id="toc-list" class="space-y-1 text-sm text-gray-700 decoration-none">
                @foreach($article->toc as $item)
                    <li class="ml-{{ ($item['level'] - 1) * 2 }} text-decoration-none">
                        <a href="#{{ $item['id'] }}"
                            class="block py-1 px-2 rounded hover:text-blue-600 scrollspy-link capitalize">
                            {{ $item['text'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>
    </x-slot>

    <div class="max-w-5xl mx-auto w-full">
        <article class="content w-full mx-auto bg-white p-8 divide-y-2 divide-gray-300">
            <div class="not-prose mb-8">
                <h1 class="text-3xl font-bold">{{ $article->title }}</h1>
                <x-articles.meta :article="$article" />
                <img src="https://miro.medium.com/v2/resize:fit:720/format:webp/0*Z_qRZVnrEP9lyFXv"
                    class="w-full max-h-96 rounded-md" alt="{{ $article->title }}">
                <div
                    class="flex justify-between items-center mt-4">
                    <div class="flex w-1/2 justify-start items-center">
                        @if ($previous)
                            <a href="{{ route('articles.show', $previous->slug) }}" class="group flex items-center bg-white hover:bg-gray-50 rounded-md shadow-sm border border-gray-100 p-2">
                                <span class="line-clamp-1 group-hover:underline group-hover:text-blue-600">
                                    ← {{ $previous->title }}
                                </span>
                            </a>
                        @endif
                    </div>
                    <div class="flex w-1/2 justify-end items-center">
                        @if ($next)
                            <a href="{{ route('articles.show', $next->slug) }}" class="group flex items-center bg-white hover:bg-gray-50 rounded-md shadow-sm border border-gray-100 p-2">
                                <span class="line-clamp-1 group-hover:underline group-hover:text-blue-600">
                                    {{ $next->title }}
                                </span>&nbsp;→
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="prose max-w-5xl no-underline">{!! $article->contents !!}</div>
        </article>

        {{-- TOC mobile --}}
        <div class="lg:hidden mt-6">
            <details class="border border-gray-300 rounded">
                <summary class="p-2 cursor-pointer font-semibold bg-gray-50">Mục lục</summary>
                <ul class="p-4 space-y-1 text-sm text-gray-700">
                    @foreach($article->toc as $item)
                        <li class="ml-{{ ($item['level'] - 1) * 4 }}">
                            <a href="#{{ $item['id'] }}" class="block py-1 px-2 rounded hover:bg-blue-100">
                                {{ $item['text'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </details>
        </div>
    </div>
    @push('js')
        @vite('resources/js/article-show.js')
    @endpush
</x-app-layout>
