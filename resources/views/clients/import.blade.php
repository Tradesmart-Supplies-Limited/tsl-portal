@extends('layouts.contentNavbarLayout')

@section('title', 'Import Clients')

@section('content')
<nav aria-label="breadcrumb" class="mb-2">
  <ol class="breadcrumb breadcrumb-style1">
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('clients.index') }}">Clients</a></li>
    <li class="breadcrumb-item active">Import</li>
  </ol>
</nav>

<div class="mb-4">
  <h4 class="mb-0">Import Clients</h4>
  <small class="text-muted">Bulk-add client records from a CSV file</small>
</div>

@if (session('success'))
  <div class="alert alert-success alert-dismissible" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if (session('import_errors') && count(session('import_errors')) > 0)
  <div class="alert alert-warning alert-dismissible" role="alert">
    <strong>Some rows needed attention:</strong>
    <ul class="mb-0 mt-2 ps-3">
      @foreach (session('import_errors') as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="row">
  <div class="col-lg-7 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-0">1. Upload your CSV</h5>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('clients.import') }}" enctype="multipart/form-data">
          @csrf

          <div class="mb-3">
            <label class="form-label" for="file">CSV file</label>
            <input type="file" id="file" name="file" accept=".csv,.txt" class="form-control @error('file') is-invalid @enderror" required>
            <small class="text-muted">Max 5MB. Must include at least <code>name</code> and <code>email</code> columns.</small>
            @error('file') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
          </div>

          <button type="submit" class="btn btn-primary">
            <i class="bx bx-upload me-1"></i> Import Clients
          </button>
          <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
      </div>
    </div>
  </div>

  <div class="col-lg-5 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-0">2. Know the format</h5>
      </div>
      <div class="card-body">
        <p class="text-muted">
          Download a sample CSV with the exact column headers we expect, pre-filled with an example row.
        </p>
        <a href="{{ route('clients.import.sample') }}" class="btn btn-outline-primary mb-4">
          <i class="bx bx-download me-1"></i> Download Sample CSV
        </a>

        <h6 class="mb-2">How it works</h6>
        <ul class="text-muted ps-3 mb-0">
          <li><code>name</code> and <code>email</code> are the only required columns — everything else is optional.</li>
          <li>Rows with an email that already exists in the system are skipped, not overwritten.</li>
          <li><code>account_manager_email</code> is matched against existing user accounts — leave blank if unsure.</li>
          <li>If you include <code>contact_name</code>, a primary contact person is created for that client automatically.</li>
          <li>You'll get a summary of what was created and what was skipped (with reasons) after upload.</li>
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
