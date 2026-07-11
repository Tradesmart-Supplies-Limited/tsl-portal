@extends('layouts.blankLayout')

@section('title', 'Complaint Submitted')

@section('content')
<div class="authentication-inner text-center" style="max-width: 560px;">
  <div class="card">
    <div class="card-body">
      <div class="text-center mb-4">
        <img src="http://misc.tradesmartzm.com/logo.png" alt="Logo" style="max-width:70px; height:auto;" />
      </div>
      
      <h4 class="mb-1">Complaint Submitted</h4>
      <div class="avatar avatar-xl mx-auto mb-3">
        <span class="avatar-initial rounded-circle bg-label-success" style="font-size: 1.75rem;">
          <i class="bx bx-check"></i>
        </span>
      </div>
      <p class="text-muted">
        Thanks, {{ $complaint->name }} — we've received your complaint and will follow up at
        <strong>{{ $complaint->email }}</strong>.
      </p>

      <div class="alert alert-primary text-start">
        <small class="d-block text-muted mb-1">Your tracking number</small>
        <h5 class="mb-0">{{ $complaint->ticket_number }}</h5>
      </div>

      <p class="text-muted">Save this number — you'll need it, along with your email, to check the status later.</p>

      <a href="{{ route('complaints.public.track.form') }}" class="btn btn-outline-primary">
        Track this complaint
      </a>
    </div>
  </div>
</div>
@endsection
