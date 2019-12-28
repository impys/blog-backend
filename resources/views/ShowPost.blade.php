@extends('layouts.app')

@section('title',$post->title)

@section('content')

<div class="w-full md:w-2/3 lg:w-1/2 m-auto px-0 md:px-4 lg:px-4">
    <h1 class="text-4xl mb-6 ml-auto text-black">{{ $post->title }}</h1>
    <div class="text-grey mb-6">创建于{{ $post->created_at_human }} / 更新于{{ $post->updated_at_human }}</div>
    <post :body='@json($post->body)'></post>
</div>


@endsection
