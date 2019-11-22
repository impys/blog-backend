@extends('layouts.app')

@section('title','动态')

@section('content')

<welcome :data='@json($data)'></welcome>

@endsection
