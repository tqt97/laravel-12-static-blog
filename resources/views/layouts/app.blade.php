<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        {{-- @include('layouts.navigation') --}}

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <header class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <a href="{{ route('posts.index') }}"
                    class="inline-block text-2xl font-extrabold hover:text-blue-500 transition-colors duration-100">
                    {{ config('app.name') }}</a>
            </div>
        </header>
        <!-- Page Content -->
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <main class="mt-16">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('pre > code').forEach((block, i) => {
            const button = document.createElement('button');
            button.textContent = 'Copy';
            button.style.position = 'absolute';
            button.style.top = '5px';
            button.style.right = '5px';
            button.style.padding = '2px 8px';
            button.style.fontSize = '12px';
            button.style.cursor = 'pointer';
            button.style.borderRadius = '4px';
            button.style.border = '1px solid rgb(62 62 62)';
            button.style.color = '#cecece';

            const wrapper = document.createElement('div');
            wrapper.style.position = 'relative';

            const pre = block.parentElement;
            pre.parentElement.replaceChild(wrapper, pre);
            wrapper.appendChild(pre);
            wrapper.appendChild(button);

            button.addEventListener('click', () => {
                navigator.clipboard.writeText(block.innerText).then(() => {
                    button.textContent = 'Copied!';
                    setTimeout(() => button.textContent = 'Copy', 1000);
                });
            });
        });
    });
</script>


</html>
