@extends('layouts.contentNavbarLayout')

@section('title', 'Upload Report')

@section('content')
<nav aria-label="breadcrumb" class="mb-2">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('reports.index') }}">Reports</a></li>
    <li class="breadcrumb-item active">Upload</li>
  </ol>
</nav>

<div class="mb-4">
  <h4 class="mb-0">Upload Report</h4>
  <small class="text-muted">Attach a file to a client's record and notify the right people</small>
</div>

<div class="card">
  <div class="card-body">
    <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="row g-4">
        <div class="col-md-6">
          <label class="form-label" for="client_id">Client <span class="text-danger">*</span></label>
          <select id="client_id" name="client_id" class="form-select @error('client_id') is-invalid @enderror" required>
            <option value="">Select a client...</option>
            @foreach ($clients as $client)
              <option value="{{ $client->id }}" @selected(old('client_id', $selectedClientId) == $client->id)>
                {{ $client->name }}
              </option>
            @endforeach
          </select>
          @error('client_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-md-6">
          <label class="form-label" for="title">Report title <span class="text-danger">*</span></label>
          <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                 value="{{ old('title') }}" required>
          @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
          <label class="form-label" for="description">Description</label>
          <textarea id="description" name="description" rows="3"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
          @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="col-12">
          <label class="form-label" for="file">File <span class="text-danger">*</span></label>
          <input type="file" id="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
          <small class="text-muted">Max 20MB.</small>
          @error('file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
        </div>

        <div class="col-12"><hr class="my-0"></div>

        <!-- Recipients -->
        <div class="col-12">
          <label class="form-label d-block">Who should be notified? <span class="text-danger">*</span></label>

          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="recipient_type" id="recipient_all" value="all"
                   @checked(old('recipient_type', 'all') === 'all')>
            <label class="form-check-label" for="recipient_all">All system users</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="recipient_type" id="recipient_specific" value="specific"
                   @checked(old('recipient_type') === 'specific')>
            <label class="form-check-label" for="recipient_specific">Specific users</label>
          </div>

          @error('recipient_type') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          @error('recipients') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror

          <div id="specificRecipients" class="mt-3" style="display: none;">
            <select name="recipients[]" id="recipients" class="form-select" multiple size="6">
              @foreach ($users as $user)
                <option value="{{ $user->id }}" @selected(collect(old('recipients', []))->contains($user->id))>
                  {{ $user->name }} ({{ $user->email }})
                </option>
              @endforeach
            </select>
            <small class="text-muted">Hold Ctrl / Cmd to select multiple users.</small>
          </div>
        </div>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bx bx-upload me-1"></i> Upload &amp; Notify
        </button>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection

@section('page-script')
<script>
  (function () {
    const allRadio = document.getElementById('recipient_all');
    const specificRadio = document.getElementById('recipient_specific');
    const specificBox = document.getElementById('specificRecipients');

    function toggle() {
      specificBox.style.display = specificRadio.checked ? 'block' : 'none';
    }

    allRadio.addEventListener('change', toggle);
    specificRadio.addEventListener('change', toggle);
    toggle();
  })();
</script>
@endsection
