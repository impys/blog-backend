@extends('layouts.app')

@section('title','动态')

@section('content')

<div class="lg:w-9/12 lg:mr-8 sm:w-full">

    @foreach ($blocks as $block)
    <block :block='@json($block)'></block>
    @endforeach

</div>

@endsection
