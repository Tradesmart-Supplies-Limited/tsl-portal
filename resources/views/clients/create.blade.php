@extends('layouts.contentNavbarLayout')

@section('title', 'New Client')

@section('content')
<div class="mb-4">
  <h4 class="mb-0">New Client</h4>
  <small class="text-muted">Add a new record to the client database</small>
</div>

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('clients.store') }}">
      @include('clients._form')
    </form>
  </div>
</div>
@endsection
