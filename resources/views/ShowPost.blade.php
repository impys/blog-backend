@extends('layouts.app')

@section('title',$post->title)

@section('content')

<post :post='@json($post)'></post>

@endsection
