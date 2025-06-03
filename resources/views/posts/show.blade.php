<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="lg:grid lg:grid-cols-4 lg:gap-8">

            {{-- Nội dung chính: chiếm 3 cột --}}
            <article class="prose prose-lg max-w-full lg:col-span-3">
                <h1>{{ $post->title }}</h1>
                <div>{!! $post->contents !!}</div>
                <x-posts.meta :post="$post" />
            </article>

            {{-- TOC: chiếm 1 cột, sticky --}}
            @if (!empty($post->toc))
                <aside
                    class="hidden lg:block sticky top-24 max-h-96  overflow-y-auto bg-white border border-gray-200 shadow rounded p-4">
                    <h2 class="text-base font-semibold mb-3">Mục lục</h2>
                    <ul id="toc-list" class="space-y-1 text-sm text-gray-700">
                        @foreach($post->toc as $item)
                            <li class="ml-{{ ($item['level'] - 1) * 4 }}">
                                <a href="#{{ $item['id'] }}" class="block py-1 px-2 rounded hover:bg-blue-100 scrollspy-link">
                                    {{ $item['text'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </aside>
            @endif

        </div>

        {{-- TOC dropdown trên mobile --}}
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const links = document.querySelectorAll('.scrollspy-link');
            const headings = Array.from(links).map(link => document.querySelector(link.getAttribute('href')));

            function onScroll() {
                const scrollTop = window.scrollY;
                let current = null;

                for (let i = 0; i < headings.length; i++) {
                    if (headings[i].offsetTop - 100 <= scrollTop) {
                        current = links[i];
                    }
                }

                links.forEach(link => link.classList.remove('text-blue-600', 'font-semibold'));
                if (current) current.classList.add('text-blue-600', 'font-semibold');
            }

            window.addEventListener('scroll', onScroll);
            onScroll();
        });
    </script>
</x-app-layout>
