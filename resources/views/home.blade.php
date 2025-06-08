<x-app-layout>
    <div class="bg-white p-6">
        <div class="flex justify-between">
            <x-articles.title>All articles</x-articles.title>
            <form action="{{ route('home') }}" class="" method="GET">
                <select name="sort" id="sort" onchange="this.form.submit()"
                    class="cursor-pointer w-full border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-gray-500 dark:focus:border-gray-600 focus:ring-gray-500 dark:focus:ring-gray-600 rounded-md shadow-sm py-[0.3rem]">
                    <option value="desc" {{ request('sort') == sortOrderDesc() ? 'selected' : '' }}>
                        Latest
                    </option>
                    <option value="asc" {{ request('sort') == sortOrderAsc() ? 'selected' : '' }}>Oldest</option>
                </select>
            </form>
        </div>

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
    </div>
</x-app-layout>
