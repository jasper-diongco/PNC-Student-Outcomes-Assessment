@extends('layouts.master')

@section('title', $curriculum->name)

@section('content')
  <div id="app">
    <h1 class="h2">{{ $curriculum->name }}</h1>
  </div>
@endsection

@push('scripts')
  @if(Session::has('message'))
    <script>
      toast.fire({
        type: 'success',
        title: '{{ Session::get('message') }}'
      })
    </script>
  @endif
@endpush