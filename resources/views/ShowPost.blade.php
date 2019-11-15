@extends('layouts.app')

@section('title','文章')

@section('content')

<div class="lg:w-2/3 sm:w-full pt-24">
    <div class="px-4">
        <h1 class="text-5xl my-6">{{ $post->title }}</h1>
        <div class="text-gray-600 my-6">
            {{ $post->created_at_human }}
        </div>
        <div class="custom__markdown">
            {!! Markdown::parse($post->body) !!}
        </div>
    </div>
</div>


@endsection
