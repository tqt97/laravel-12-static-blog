<li class="group prose my-4 relative flex flex-col sm:flex-row xl:flex-col items-center">
    <div class="order-1 sm:order-2 sm:ml-6 xl:ml-0">
        <div class="flex justify-between items-center gap-2">
            <div class="not-prose text-sm flex items-center gap-4 my-2">
                <div class="flex items-1 gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span> {{ $post->author }} </span>
                </div>
                <div class="flex items-end1 gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    {{ $post->date->toDateString() }}
                </div>
            </div>
            @if (count($post->tags))
                <div class="flex">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                    </svg>
                    <ul class="not-prose px-1 list-none flex items-center divide-x divide-gray-200">
                        @foreach ($post->tags as $tag)
                            <li class="text-sm">
                                <a href="{{ route('tags.show', $tag) }}" class="hover:text-blue-500 p-1">
                                    {{ Str::title($tag) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <h2 class="mb-1 text-slate-900 font-semibold not-prose">
            <a href="{{ route('posts.show', $post->slug) }}"
                class="text-2xl font-bold hover:text-blue-500 transition-colors duration-100 line-clamp-2">{{ $post->title }}</a>
        </h2>
        <div class="prose prose-slate prose-sm text-slate-600 mt-2">
            <div class="line-clamp-2">
                {{ $post->teaser }}
            </div>
        </div>
        {{-- <a
            class="group inline-flex items-center h-9 rounded-full text-sm font-semibold whitespace-nowrap px-3 focus:outline-none focus:ring-2 bg-slate-100 text-slate-700 hover:bg-slate-200 hover:text-slate-900 focus:ring-slate-500 mt-6"
            href="">Learn
            more<span class="sr-only">, Completely unstyled, fully accessible UI components</span>
            <svg class="overflow-visible ml-3 text-slate-300 group-hover:text-slate-400" width="3" height="6"
                viewBox="0 0 3 6" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round">
                <path d="M0 0L3 3L0 6"></path>
            </svg>
        </a> --}}
    </div>
    <a href="{{ route('posts.show', $post->slug) }}" title="{{ $post->title }}">
        <img src="https://miro.medium.com/v2/resize:fit:720/format:webp/0*Z_qRZVnrEP9lyFXv" alt=""
            class="mb-1 shadow-md rounded-lg bg-slate-50 w-full sm:w-[17rem] sm:mb-0 xl:mb-2 xl:w-full group-hover:shadow-lg"
            width="1216" height="640">
    </a>
</li>
