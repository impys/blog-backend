@extends('layouts.app')

@section('title','首页')


@section('content')

<div class="flex flex-col lg:flex-row justify-between ">
    <div class="lg:w-3/4 sm:w-full">
        <post-cards :posts='@json($posts)'></post-cards>
        <div class="my-2">
            {{ $posts->links() }}
        </div>
    </div>
    <div class="lg:w-1/4 ml-4">
        @yield('sidebar')
    </div>
</div>


@endsection
