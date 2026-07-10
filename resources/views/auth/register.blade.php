@extends('layouts.blankLayout')

@section('title', 'Create Account')

@section('content')
<div class="authentication-inner" style="max-width: 440px;">
  <div class="card">
    <div class="card-body">
      <div class="text-center mb-4">
        <h4 class="mb-1">Create your account 🚀</h4>
        <p class="text-muted mb-0">Get set up on the client portal in a minute</p>
      </div>

      @if ($errors->any())
        <div class="alert alert-danger">
          @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label" for="name">Full name</label>
          <input
            type="text"
            id="name"
            name="name"
            class="form-control"
            value="{{ old('name') }}"
            autofocus
            required
          />
        </div>

        <div class="mb-3">
          <label class="form-label" for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required />
        </div>

        <div class="mb-3">
          <label class="form-label" for="password">Password</label>
          <input type="password" id="password" name="password" class="form-control" required />
          <small class="text-muted">At least 8 characters.</small>
        </div>

        <div class="mb-3">
          <label class="form-label" for="password_confirmation">Confirm password</label>
          <input
            type="password"
            id="password_confirmation"
            name="password_confirmation"
            class="form-control"
            required
          />
        </div>

        <button type="submit" class="btn btn-primary d-block w-100">Create Account</button>
      </form>

      <p class="text-center mt-4 mb-0">
        <span>Already have an account?</span>
        <a href="{{ route('login') }}">
          <span>Log in instead</span>
        </a>
      </p>
    </div>
  </div>
</div>
@endsection
