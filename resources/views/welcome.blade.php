@extends('layouts.app')

@section('title','动态')

@section('content')

<div class="lg:w-9/12 lg:mr-8 sm:w-full">
    <block :blocks='@json($blocks)'></block>
</div>
<div class="w-3/12 hidden bg-black sm:hidden lg:block">
</div>

@endsection
