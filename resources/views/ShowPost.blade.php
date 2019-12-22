@extends('layouts.app')

@section('title',$post->title)

@section('content')

<div class="lg:w-2/3 sm:w-full">
    <post :post='@json($post)'></post>
</div>


@endsection
