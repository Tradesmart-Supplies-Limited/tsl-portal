@extends('layouts.contentNavbarLayout')

@section('title', 'Edit Client')

@section('content')
<div class="mb-4">
  <h4 class="mb-0">Edit Client</h4>
  <small class="text-muted">{{ $client->name }}</small>
</div>

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('clients.update', $client) }}">
      @method('PUT')
      @include('clients._form')
    </form>
  </div>
</div>
@endsection
