@extends('layouts.blankLayout')

@section('title', 'Submit a Complaint')

@section('content')
<div class="authentication-inner" style="max-width: 640px;">
  <div class="card">
        <div class="card-body">
          <div class="text-center mb-4">
            <h4 class="mb-1">Submit a Complaint</h4>
            <p class="text-muted mb-0">Let us know what went wrong — we'll follow up by email.</p>
          </div>

          @if ($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <form method="POST" action="{{ route('complaints.public.store') }}">
            @csrf

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="name">Your name <span class="text-danger">*</span></label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="email">Email <span class="text-danger">*</span></label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
              </div>

              <div class="col-md-6">
                <label class="form-label" for="phone">Phone</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone') }}">
              </div>

              <div class="col-md-6">
                <label class="form-label" for="priority">Urgency</label>
                <select id="priority" name="priority" class="form-select">
                  <option value="low" @selected(old('priority') === 'low')>Low</option>
                  <option value="medium" @selected(old('priority', 'medium') === 'medium')>Medium</option>
                  <option value="high" @selected(old('priority') === 'high')>High</option>
                  <option value="urgent" @selected(old('priority') === 'urgent')>Urgent</option>
                </select>
              </div>

              <div class="col-12">
                <label class="form-label" for="subject">Subject <span class="text-danger">*</span></label>
                <input type="text" id="subject" name="subject" class="form-control" value="{{ old('subject') }}" required>
              </div>

              <div class="col-12">
                <label class="form-label" for="category">Category</label>
                <input type="text" id="category" name="category" class="form-control"
                       placeholder="e.g. Billing, Service quality, Delivery" value="{{ old('category') }}">
              </div>

              <div class="col-12">
                <label class="form-label" for="description">What happened? <span class="text-danger">*</span></label>
                <textarea id="description" name="description" rows="5" class="form-control" required>{{ old('description') }}</textarea>
              </div>
            </div>

            <button type="submit" class="btn btn-primary d-block w-100 mt-4">
              Submit Complaint
            </button>
          </form>

      <p class="text-center mt-3 mb-0">
        <a href="{{ route('complaints.public.track.form') }}">Already submitted? Track your complaint</a>
      </p>
    </div>
  </div>
</div>
@endsection
