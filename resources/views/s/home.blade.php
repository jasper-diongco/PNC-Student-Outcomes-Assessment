@extends('layout.app', ['active' => 'home-student'])


@section('title', 'Home')

@section('content')
    <div id="app">
        <h1 class="page-header">Home</h1>
        <div class="card">
            <div class="card-body pt-4">
                <h2 style="font-weight: 300; font-size: 35px">Hello, {{ ucwords(strtolower(auth()->user()->first_name)) }}. Welcome Back!</h2>
            </div>
        </div>
    </div>
@endsection