<div class="text-sm flex items-center gap-4 mt-2 mb-4 border-b-2 border-dotted border-gray-300">
    <div class="not-prose text-sm flex items-center1 gap-4 my-2">
        <div class="flex items-center1 gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
            {{ $post->author }}
        </div>
        <div class="flex items-center1 gap-1">
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
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5-3.9 19.5m-2.1-19.5-3.9 19.5" />
            </svg>


            <ul class="not-prose p-0 list-none flex divide-x divide-gray-200 gap-1">
                @foreach ($post->tags as $tag)
                    <li class="text-sm">
                        <a href="{{ route('tags.show', $tag) }}" class="hover:text-blue-500 px-1">
                            {{ Str::title($tag) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
