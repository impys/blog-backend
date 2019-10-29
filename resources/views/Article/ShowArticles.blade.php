@extends('layouts.app')

@section('title','文章')

@section('content')

<div class="lg:w-9/12 lg:mr-8 sm:w-full">

    <block :block='@json($data)'></block>

</div>

@endsection
