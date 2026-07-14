@extends('layouts.blankLayout')

@section('title', 'Complaint Status')

@section('content')
<div class="authentication-inner" style="max-width: 640px;">
  <div class="card">
    <div class="card-body">
      <div class="text-center mb-4">
        <img src="http://misc.tradesmartzm.com/logo.png" alt="Logo" style="max-width:70px; height:auto;" />
      </div>
      <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
          <h5 class="mb-1">{{ $complaint->subject }}</h5>
          <small class="text-muted">Ticket #{{ $complaint->ticket_number }}</small>
        </div>
        @php
          $statusColors = ['open' => 'danger', 'in_progress' => 'warning', 'resolved' => 'success', 'closed' => 'secondary'];
          $statusLabels = ['open' => 'Received', 'in_progress' => 'In progress', 'resolved' => 'Resolved', 'closed' => 'Closed'];
        @endphp
        <span class="badge bg-label-{{ $statusColors[$complaint->status] }}">
          {{ $statusLabels[$complaint->status] }}
        </span>
      </div>

      <p class="text-muted">{{ $complaint->description }}</p>

      @if ($complaint->hasAttachment())
        <p class="mb-3">
          <i class="bx bx-paperclip me-1"></i>
          Attached: <a href="{{ route('complaints.public.attachment', $complaint) }}">{{ $complaint->attachment_name }}</a>
          <small class="text-muted">({{ $complaint->human_attachment_size }})</small>
        </p>
      @endif

      <hr>

      <h6 class="mb-3">Updates</h6>
      @forelse ($complaint->replies as $reply)
        <div class="d-flex mb-3">
          <div class="avatar avatar-sm flex-shrink-0 me-3">
            <span class="avatar-initial rounded-circle bg-label-{{ $reply->isFromStaff() ? 'primary' : 'info' }}">
              {{ strtoupper(substr($reply->isFromStaff() ? $reply->user->name : $complaint->name, 0, 1)) }}
            </span>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2">
              <span class="fw-semibold">{{ $reply->isFromStaff() ? 'Support team' : $complaint->name }}</span>
              <small class="text-muted">{{ $reply->created_at->format('M d, Y') }}</small>
            </div>
            <p class="mb-0 mt-1">{{ $reply->message }}</p>
          </div>
        </div>
      @empty
        <p class="text-muted">No updates yet — our team has received your complaint and will respond soon.</p>
      @endforelse

      <hr>
      <p class="text-center mb-0">
        <a href="{{ route('complaints.public.track.form') }}">Check another complaint</a>
      </p>
    </div>
  </div>
</div>
@endsection
