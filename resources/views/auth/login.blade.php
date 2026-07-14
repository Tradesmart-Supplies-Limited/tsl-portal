@extends('layouts.blankLayout')

@section('title', 'Log In')
@section('description', 'Log in to your Tradesmart Client Portal account to access your client information and manage your relationships.')

@section('content')
<div class="authentication-inner" style="max-width: 440px;">
  <div class="card">
    <div class="card-body">

     <div class="text-center mb-4">
        <img src="http://misc.tradesmartzm.com/logo.png" alt="Logo" style="max-width:70px; height:auto;" />
      </div>
      <div class="text-center mb-4">
        <h4 class="mb-1">Welcome back</h4>
        <p class="text-muted mb-0">Log in to your client portal account</p>
      </div>

      @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
      @endif

      @if ($errors->any())
        <div class="alert alert-danger">
          @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label" for="email">Email</label>
          <input
            type="email"
            id="email"
            name="email"
            class="form-control"
            value="{{ old('email') }}"
            autofocus
            required
          />
        </div>

        <div class="mb-3">
          <label class="form-label" for="password">Password</label>
          <input type="password" id="password" name="password" class="form-control" required />
        </div>

        <div class="mb-3 d-flex justify-content-between align-items-center">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1" />
            <label class="form-check-label" for="remember">Remember me</label>
          </div>
        </div>

        <button type="submit" class="btn btn-primary d-block w-100">Log In</button>
      </form>

      <p class="text-center mt-4 mb-0">
        <span>New to the portal?</span>
        <a href="{{ route('register') }}">
          <span>Create an account</span>
        </a>
      </p>
    </div>
  </div>
</div>
@endsection
