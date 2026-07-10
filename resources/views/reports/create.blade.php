@extends('layouts.contentNavbarLayout')

@section('title', 'Upload Report')

@section('content')
<div class="mb-4">
  <h4 class="mb-0">Upload Report</h4>
  <small class="text-muted">Attach a file to a client's record</small>
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
      </div>

      <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bx bx-upload me-1"></i> Upload
        </button>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </form>
  </div>
</div>
@endsection
