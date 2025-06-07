<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

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
    @stack('css')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 mx-auto w-full relative1">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 min-h-vh rounded-md relative1">
            <main class="relative bg-white px-4 mt-8 py-6 mx-auto rounded-md">
                {{ $slot }}
                @isset($toc)
                    {{ $toc }}
                @endisset
            </main>
        </div>
    </div>
    <x-back-to-top />
    <script>
        window.addEventListener('scroll', function () {
            const button = document.getElementById('backToTop');
            if (window.scrollY > 150) {
                button.style.display = 'block';
            } else {
                button.style.display = 'none';
            }
        });
        document.getElementById('backToTop').addEventListener('click', function () {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    </script>
    @stack('js')
</body>

</html>
