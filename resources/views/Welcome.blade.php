@extends('layouts.app')

@section('title','首页')


@section('content')

<div class="flex flex-col md:flex-row lg:flex-row justify-between ">
    <div class="w-full md:w-2/3 lg:w-3/4 mr-0 md:mr-8 lg:mr-8">
        <post-cards :posts='@json($posts)'></post-cards>
        <div class="my-2">
            {{ $posts->links() }}
        </div>
    </div>
    <div class="w-full md:w-1/3 lg:w-1/4">
        @yield('sidebar')
    </div>
</div>


@endsection
