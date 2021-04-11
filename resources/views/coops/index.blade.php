@extends('layouts.app')

@section('content')
    @foreach ($coops as $coop)
        <div>{{ $loop->inde }}</div>
        <div>{{ $coop->name }}</div>
    @endforeach
@endsection
