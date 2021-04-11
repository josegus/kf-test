@extends('layouts.app')

@section('content')
    @if(session()->has('success'))
        <div>{{ session('success') }}</div>
    @endif

    <div>Thanks for purchasing</div>

    <h2>{{ $coop->name }}</h2>
@endsection
