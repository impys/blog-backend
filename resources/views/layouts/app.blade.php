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
            <nav class="container mx-auto py-3 h-16 pl-4 pr-4 lg:pr-0 flex items-start justify-between">
                <div class="mr-4 flex-shrink-0">
                    <a href="/" class="text-3xl pr-4">
                        <img src="{{ asset('img/logo.png') }}" alt="qingfeng.blog" class="h-10 w-10">
                    </a>
                </div>
                <search></search>

            </nav>
        </header>

        <div class="container mx-auto py-24">
            @yield('content')
        </div>
    </div>
</body>

</html>
