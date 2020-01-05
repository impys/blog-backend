<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-screen">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>青风百里的博客</title>

    <script src="{{ asset('js/app.js') }}" defer></script>

    <script src="//at.alicdn.com/t/font_1597329_k5afjyzn74p.js"></script>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="text-black antialiased leading-tight bg-white">
    <div id="app" class="container mx-auto px-3 lg:px-0">
        <app></app>
    </div>
</body>

</html>
