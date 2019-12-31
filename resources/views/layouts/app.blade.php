<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-screen">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - 青风百里的博客</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="text-black antialiased leading-tight bg-white">
    <div id="app" class="relative">

        <header class="h-16 fixed w-full bg-white z-20">
            <nav class="container mx-auto py-3 px-3 lg:px-0 h-16 flex items-start justify-between">
                <div class="flex-shrink-0 h-10 w-10 mr-2 lg:mr-0">
                    <a href="/" class="text-3xl">
                        <img src="{{ asset('img/logo.png') }}" alt="qingfeng.blog">
                    </a>
                </div>
                <div class="w-full md:w-1/3 lg:w-1/4 py-1 lg:py-0">
                    <search></search>
                </div>
                <div
                    class="flex items-center justify-center flex-shrink-0 h-10 w-10 ml-2 block md:hidden lg:hidden text-4xl text-ching">
                    <i class="fas fa-bars block"></i>
                </div>
            </nav>
        </header>

        <div class="container mx-auto pt-16 pb-12 px-4 lg:px-0">
            @yield('content')
        </div>
    </div>
</body>

</html>
