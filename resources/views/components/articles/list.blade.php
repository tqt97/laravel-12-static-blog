<li class="group prose relative flex flex-col sm:flex-row xl:flex-col items-center">
    <div class="order-1 sm:order-2 sm:ml-6 xl:ml-0 w-full">
        <div class="flex justify-between items-center gap-2">
            <div class="not-prose text-sm flex items-center gap-4 my-2">
                <div class="flex items-1 gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span> {{ $article->author }} </span>
                </div>
                <div class="flex items-end1 gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                    {{ $article->date->toDateString() }}
                </div>
            </div>
            @if (count($article->tags))
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-3">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
                    </svg>
                    <ul class="not-prose list-none flex items-center divide-gray-200 gap-1">
                        @foreach ($article->tags as $tag)
                            <li class="text-sm">
                                <a href="{{ route('tags.show', $tag) }}" class="text-blue-800 hover:text-blue-600">
                                    {{ Str::title($tag) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <h2 class="mb-1 text-slate-900 font-semibold not-prose">
            <a href="{{ route('articles.show', $article->slug) }}"
                class="text-xl font-bold hover:text-blue-500 transition-colors duration-100 line-clamp-2">
                {!! highlight($article->title, request('search')) !!}
            </a>
        </h2>
        <div class="prose prose-slate prose-sm text-slate-600 mt-2">
            <div class="line-clamp-2">
                {{ $article->teaser }}
            </div>
        </div>
    </div>
    <a href="{{ route('articles.show', $article->slug) }}" title="{{ $article->title }}">
        <img src="https://miro.medium.com/v2/resize:fit:720/format:webp/0*Z_qRZVnrEP9lyFXv" alt=""
            class="mb-1 shadow-md rounded-lg bg-slate-50 w-full sm:w-[17rem] sm:mb-0 xl:mb-2 xl:w-full group-hover:shadow-lg"
            width="1200" height="630">
    </a>
</li>
