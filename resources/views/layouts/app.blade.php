<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    <!-- Inline SVG favicon (Laravel-style red logo) -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjZGMxNDQ3IiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyNCAyNCI+PHBhdGggZD0iTTEyIDMgTDIgOSA2IDkgNiAxNSAxOCAxNSAxOCA5IDE4IDkgMTIgMTgiLz48L3N2Zz4=" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gradient-to-r from-pink-400 via-purple-600 to-pink-600 min-h-screen">
    <div class="min-h-screen flex flex-col">
        @include('layouts.navigation')

        @if (isset($header))
            <header class="bg-white shadow-md rounded-b-md">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-3xl font-semibold text-gray-800 tracking-wide">
                        {{ $header }}
                    </h1>
                </div>
            </header>
        @endif

        <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white shadow-lg rounded-lg p-8">
                @yield('content')
            </div>
        </main>

        <footer class="bg-white shadow-inner py-4 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.
        </footer>
    </div>
</body>
</html>
