@extends('layouts.app')

@section('title','动态')

@section('content')

<block :blocks='@json($blocks)'></block>

@endsection
