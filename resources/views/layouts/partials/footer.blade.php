<footer class="content-footer footer bg-footer-theme">
  <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
    <div class="mb-2 mb-md-0">
      &copy; {{ now()->year }} {{ config('app.name', 'Client Portal') }}. All rights reserved.
    </div>
    <div>
      <a href="{{ route('complaints.public.create') }}" class="footer-link me-4">Submit a Complaint</a>
      <a href="{{ route('complaints.public.track.form') }}" class="footer-link">Track a Complaint</a>
    </div>
  </div>
</footer>
