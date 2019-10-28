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

        <header class="fixed top-0 z-20 bg-white w-full h-16">
            <nav
                class="flex justify-between flex-row text-gray-700 items-center flex-wrap py-2 px-4 lg:px-4 text-4xl container mx-auto">
                <a href="/" class="">Moreless</a>
            </nav>
        </header>


        <div class="pt-24 flex flex-row justify-between container mx-auto">
            @yield('content')
        </div>

    </div>
</body>

</html>
