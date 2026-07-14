@extends('layouts.contentNavbarLayout')

@section('title', 'Complaint #' . $complaint->ticket_number)

@section('content')
<div class="mb-4">
  <a href="{{ route('complaints.index') }}" class="text-muted"><i class="bx bx-arrow-back me-1"></i> Back to Complaint Center</a>
</div>

@if (session('success'))
  <div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="row">
  <div class="col-lg-8">
    <div class="card mb-4">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h5 class="mb-0">{{ $complaint->subject }}</h5>
          <small class="text-muted">Ticket #{{ $complaint->ticket_number }} · Submitted {{ $complaint->created_at->format('M d, Y \a\t g:i A') }}</small>
        </div>
      </div>
      <div class="card-body">
        <p class="mb-0">{{ $complaint->description }}</p>
        @if ($complaint->hasAttachment())
          <div class="mt-3 d-flex align-items-center gap-2">
            <span class="avatar avatar-sm">
              <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-paperclip"></i></span>
            </span>
            <div>
              <a href="{{ route('complaints.attachment', $complaint) }}" class="fw-semibold">{{ $complaint->attachment_name }}</a>
              <small class="text-muted d-block">{{ $complaint->human_attachment_size }}</small>
            </div>
          </div>
        @endif
      </div>
    </div>

    <!-- Conversation thread -->
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Conversation</h5>
      </div>
      <div class="card-body">
        @forelse ($complaint->replies as $reply)
          <div class="d-flex mb-4 {{ $reply->is_internal_note ? 'opacity-75' : '' }}">
            <div class="avatar avatar-sm flex-shrink-0 me-3">
              <span class="avatar-initial rounded-circle bg-label-{{ $reply->isFromStaff() ? 'primary' : 'info' }}">
                {{ strtoupper(substr($reply->isFromStaff() ? $reply->user->name : ($reply->author_name ?: $complaint->name), 0, 1)) }}
              </span>
            </div>
            <div class="flex-grow-1">
              <div class="d-flex align-items-center gap-2">
                <span class="fw-semibold">{{ $reply->isFromStaff() ? $reply->user->name : $complaint->name }}</span>
                @if ($reply->isFromStaff())
                  <span class="badge bg-label-primary">Staff</span>
                @else
                  <span class="badge bg-label-info">Client</span>
                @endif
                @if ($reply->is_internal_note)
                  <span class="badge bg-label-secondary">Internal note</span>
                @endif
                <small class="text-muted">{{ $reply->created_at->format('M d, Y g:i A') }}</small>
              </div>
              <p class="mb-0 mt-1">{{ $reply->message }}</p>
            </div>
          </div>
        @empty
          <p class="text-muted mb-0">No replies yet.</p>
        @endforelse
      </div>

      <div class="card-body border-top">
        <form method="POST" action="{{ route('complaints.reply', $complaint) }}">
          @csrf
          <div class="mb-3">
            <label class="form-label" for="message">Post a reply</label>
            <textarea id="message" name="message" rows="3" class="form-control @error('message') is-invalid @enderror" required>{{ old('message') }}</textarea>
            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <div class="form-check">
              <input type="checkbox" id="is_internal_note" name="is_internal_note" value="1" class="form-check-input">
              <label class="form-check-label" for="is_internal_note">Internal note (not visible to client)</label>
            </div>
            <button type="submit" class="btn btn-primary">
              <i class="bx bx-send me-1"></i> Post reply
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <!-- Submitter info -->
    <div class="card mb-4">
      <div class="card-header">
        <h6 class="mb-0">Submitted by</h6>
      </div>
      <div class="card-body">
        <p class="mb-1"><strong>{{ $complaint->name }}</strong></p>
        <p class="mb-1 text-muted">{{ $complaint->email }}</p>
        <p class="mb-0 text-muted">{{ $complaint->phone ?: 'No phone provided' }}</p>
        @if ($complaint->client)
          <hr>
          <a href="{{ route('clients.show', $complaint->client) }}" class="btn btn-sm btn-outline-primary w-100">
            <i class="bx bx-user me-1"></i> View client record
          </a>
        @else
          <hr>
          <small class="text-muted">No matching client record — submitted as a guest.</small>
        @endif
      </div>
    </div>

    <!-- Manage complaint -->
    <div class="card">
      <div class="card-header">
        <h6 class="mb-0">Manage</h6>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('complaints.update', $complaint) }}">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label" for="status">Status</label>
            <select id="status" name="status" class="form-select">
              <option value="open" @selected($complaint->status === 'open')>Open</option>
              <option value="in_progress" @selected($complaint->status === 'in_progress')>In progress</option>
              <option value="resolved" @selected($complaint->status === 'resolved')>Resolved</option>
              <option value="closed" @selected($complaint->status === 'closed')>Closed</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label" for="priority">Priority</label>
            <select id="priority" name="priority" class="form-select">
              <option value="low" @selected($complaint->priority === 'low')>Low</option>
              <option value="medium" @selected($complaint->priority === 'medium')>Medium</option>
              <option value="high" @selected($complaint->priority === 'high')>High</option>
              <option value="urgent" @selected($complaint->priority === 'urgent')>Urgent</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label" for="assigned_to">Assigned to</label>
            <select id="assigned_to" name="assigned_to" class="form-select">
              <option value="">Unassigned</option>
              @foreach ($staff as $member)
                <option value="{{ $member->id }}" @selected($complaint->assigned_to === $member->id)>
                  {{ $member->name }}
                </option>
              @endforeach
            </select>
          </div>

          <button type="submit" class="btn btn-primary w-100">
            <i class="bx bx-save me-1"></i> Update
          </button>
        </form>

        <form action="{{ route('complaints.destroy', $complaint) }}" method="POST" class="mt-2"
              onsubmit="return confirm('Delete this complaint? This cannot be undone.')">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-outline-danger w-100">
            <i class="bx bx-trash me-1"></i> Delete complaint
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
