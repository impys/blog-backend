<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-screen">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Moreless Blog</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="text-gray-700 antialiased leading-tight">
    <div id="app">
        <header class="h-16 fixed w-full bg-white z-20">
            <nav class="container mx-auto py-3 px-4 h-16 text-gray-700 flex items-start justify-between">
                <a href="/" class="text-3xl block flex-shrink-0">
                    <img src="{{ asset('img/logo.png') }}" alt="moreless" class="h-10 w-10">
                </a>
                <search></search>
            </nav>
        </header>

        <div class="container mx-auto">
            <div class="flex justify-between pt-24">
                @yield('content')
            </div>

            <footer class="w-full h-10 bg-white">
                <div class="py-10 px-4">
                    版权
                </div>
            </footer>
        </div>
    </div>
</body>

</html>
