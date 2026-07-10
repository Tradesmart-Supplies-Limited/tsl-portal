@extends('layouts.contentNavbarLayout')

@section('title', $report->title)

@section('content')
<div class="mb-4">
  <a href="{{ route('clients.show', $report->client) }}" class="text-muted">
    <i class="bx bx-arrow-back me-1"></i> Back to {{ $report->client->name }}
  </a>
</div>

<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center">
      <div class="avatar avatar-md me-3">
        <span class="avatar-initial rounded bg-label-info"><i class="bx bx-file"></i></span>
      </div>
      <div>
        <h5 class="mb-0">{{ $report->title }}</h5>
        <small class="text-muted">{{ $report->file_name }} · {{ $report->human_file_size }}</small>
      </div>
    </div>
    <a href="{{ route('reports.download', $report) }}" class="btn btn-primary">
      <i class="bx bx-download me-1"></i> Download
    </a>
  </div>
  <div class="card-body">
    <ul class="list-unstyled mb-0">
      <li class="d-flex justify-content-between py-2 border-bottom">
        <span class="text-muted">Client</span>
        <a href="{{ route('clients.show', $report->client) }}">{{ $report->client->name }}</a>
      </li>
      <li class="d-flex justify-content-between py-2 border-bottom">
        <span class="text-muted">Uploaded by</span>
        <span>{{ $report->uploader->name ?? 'System' }}</span>
      </li>
      <li class="d-flex justify-content-between py-2 border-bottom">
        <span class="text-muted">Uploaded on</span>
        <span>{{ $report->created_at->format('M d, Y \a\t g:i A') }}</span>
      </li>
    </ul>
    @if ($report->description)
      <p class="mt-3 mb-0">{{ $report->description }}</p>
    @endif
  </div>
</div>
@endsection
