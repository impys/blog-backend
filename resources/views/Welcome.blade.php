@extends('layouts.app')

@section('title','首页')

@section('content')

<welcome :data='@json($data)'></welcome>

@endsection
