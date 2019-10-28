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
    <div id="app" class="container mx-auto">



        <header class="-mx-1 lg:-mx-4 ">
            <nav class="flex justify-between flex-row text-red-400 items-center flex-wrap py-3 px-4 lg:px-4 text-4xl">
                <a href="" class="">Moreless</a>
            </nav>
        </header>



        <div class="mt-4 flex flex-row justify-between">
            <div class="lg:w-9/12 lg:mr-8 sm:w-full">
                @yield('content')
            </div>
            <div class="w-3/12 hidden bg-black sm:hidden lg:block">
            </div>
        </div>


    </div>
</body>

</html>
