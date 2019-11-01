@extends('layouts.app')

@section('title','动态')

@section('content')

<div class="lg:w-3/4 lg:mr-8 sm:w-full">

    @foreach ($data as $block)
    <block :block='@json($block)'></block>
    @endforeach

</div>

@endsection
