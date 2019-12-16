@extends('layouts.app')

@section('title','文章')

@section('content')

<div class="lg:w-2/3 sm:w-full">
    <post :post='@json($post)'></post>
</div>


@endsection
