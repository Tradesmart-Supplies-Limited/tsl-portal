@extends('layouts.blankLayout')

@section('title', 'Track Your Complaint')

@section('content')
<div class="authentication-inner" style="max-width: 480px;">
  <div class="card">
    <div class="card-body">
      <div class="text-center mb-4">
        <img src="http://misc.tradesmartzm.com/logo.png" alt="Logo" style="max-width:70px; height:auto;" />
      </div>
      <div class="text-center mb-4">
        <h4 class="mb-1">Track Your Complaint</h4>
        <p class="text-muted mb-0">Enter your ticket number and email to see the latest status.</p>
      </div>

      @if ($errors->any())
        <div class="alert alert-danger">
          Couldn't find a complaint matching those details — double check your ticket number and email.
        </div>
      @endif

      <form method="POST" action="{{ route('complaints.public.track') }}">
        @csrf
        <div class="mb-3">
          <label class="form-label" for="ticket_number">Ticket number</label>
          <input type="text" id="ticket_number" name="ticket_number" class="form-control"
                 placeholder="CMP-2026-XXXXXX" value="{{ old('ticket_number') }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label" for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control"
                 value="{{ old('email') }}" required>
        </div>
        <button type="submit" class="btn btn-primary d-block w-100">Check Status</button>
      </form>

      <p class="text-center mt-3 mb-0">
        <a href="{{ route('complaints.public.create') }}">Submit a new complaint instead</a>
      </p>
    </div>
  </div>
</div>
@endsection
