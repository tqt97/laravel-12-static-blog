<x-app-layout>
    <x-slot name="toc">
        <aside
            class="hidden lg:block fixed right-24 top-1/4 max-h-96 overflow-y-auto bg-white border border-gray-200 shadow rounded p-4">
            <h2 class="text-base font-semibold mb-3 border-b">Table of contents</h2>
            <ul id="toc-list" class="space-y-1 text-sm text-gray-700 decoration-none">
                @foreach($post->toc as $item)
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

    <div class="max-w-5xl mx-auto px-4 w-full">
        <article class="content w-full mx-auto">
            <div class="">
                <h1 class="text-3xl font-bold">{{ $post->title }}</h1>
                <x-posts.meta :post="$post" />
            </div>
            <div class="prose max-w-5xl no-underline">{!! $post->contents !!}</div>
        </article>

        {{-- TOC mobile --}}
        <div class="lg:hidden mt-6">
            <details class="border border-gray-300 rounded">
                <summary class="p-2 cursor-pointer font-semibold bg-gray-50">Mục lục</summary>
                <ul class="p-4 space-y-1 text-sm text-gray-700">
                    @foreach($post->toc as $item)
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
        @vite('resources/js/post-show.js')
    @endpush
</x-app-layout>
