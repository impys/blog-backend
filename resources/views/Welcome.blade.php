@extends('layouts.app')

@section('title','动态')

@section('content')



    @foreach ($data as $block)
    <block :block='@json($block)'></block>
    @endforeach



@endsection
