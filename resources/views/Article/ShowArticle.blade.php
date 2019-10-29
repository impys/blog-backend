@extends('layouts.app')

@section('title','文章')

@section('content')


<div class="lg:w-1/2 sm:w-full">
    <div class="px-4">
        <h1 class="font-bold text-5xl my-6">{{ $article->title }}</h1>
        <div class="text-gray-600 my-6">{{ $article->created_at_human }}</div>
        <div class="custom__markdown">
            {!! Markdown::parse($article->body) !!}
        </div>
    </div>
</div>

@endsection
