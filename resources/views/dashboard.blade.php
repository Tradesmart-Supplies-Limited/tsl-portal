@extends('layouts.contentNavbarLayout')

@section('title', 'Dashboard')

@section('content')
<div class="row">
  <!-- Welcome / quick actions -->
  <div class="col-12 mb-4">
    <div class="card">
      <div class="d-flex align-items-end row">
        <div class="col-sm-8">
          <div class="card-body">
            <h5 class="card-title text-primary">Welcome back, {{ auth()->user()->name ?? 'there' }} 👋</h5>
            <p class="mb-4">
              Here's what's happening across your client portal —
              <span class="fw-bold">{{ $totalClients }}</span> clients on file and
              <span class="fw-bold">{{ $openComplaints }}</span> complaints currently open.
            </p>
            <a href="{{ route('clients.create') }}" class="btn btn-sm btn-primary me-2">
              <i class="bx bx-plus me-1"></i> New client
            </a>
            <a href="{{ route('complaints.index') }}" class="btn btn-sm btn-outline-primary">
              <i class="bx bx-conversation me-1"></i> View complaints
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Stat cards -->
<div class="row">
  <div class="col-lg-3 col-md-6 col-6 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-group"></i></span>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Total Clients</span>
        <h3 class="card-title mb-2">{{ number_format($totalClients) }}</h3>
        <small class="text-muted">{{ $activeClients }} active</small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-6 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-file"></i></span>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Reports Uploaded</span>
        <h3 class="card-title mb-2">{{ number_format($totalReports) }}</h3>
        <small class="text-muted">across all clients</small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-6 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-error-circle"></i></span>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Open Complaints</span>
        <h3 class="card-title mb-2">{{ number_format($openComplaints) }}</h3>
        <small class="text-muted">{{ $complaintCounts['open'] }} new · {{ $complaintCounts['in_progress'] }} in progress</small>
      </div>
    </div>
  </div>

  <div class="col-lg-3 col-md-6 col-6 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-time-five"></i></span>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Avg. Resolution Time</span>
        <h3 class="card-title mb-2">{{ $avgResolutionDays }}<small class="fs-6 fw-normal"> days</small></h3>
        <small class="text-muted">based on resolved complaints</small>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Trend chart -->
  <div class="col-lg-8 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Activity — Last 6 Months</h5>
      </div>
      <div class="card-body">
        <div id="activityTrendChart"></div>
      </div>
    </div>
  </div>

  <!-- Complaint status breakdown -->
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-0">Complaint Status</h5>
      </div>
      <div class="card-body">
        <div id="complaintStatusChart"></div>
        <ul class="p-0 m-0 mt-3">
          <li class="d-flex align-items-center justify-content-between mb-2">
            <span><span class="badge bg-danger rounded-circle p-1 me-2"></span>Open</span>
            <span class="fw-semibold">{{ $complaintCounts['open'] }}</span>
          </li>
          <li class="d-flex align-items-center justify-content-between mb-2">
            <span><span class="badge bg-warning rounded-circle p-1 me-2"></span>In progress</span>
            <span class="fw-semibold">{{ $complaintCounts['in_progress'] }}</span>
          </li>
          <li class="d-flex align-items-center justify-content-between mb-2">
            <span><span class="badge bg-success rounded-circle p-1 me-2"></span>Resolved</span>
            <span class="fw-semibold">{{ $complaintCounts['resolved'] }}</span>
          </li>
          <li class="d-flex align-items-center justify-content-between">
            <span><span class="badge bg-secondary rounded-circle p-1 me-2"></span>Closed</span>
            <span class="fw-semibold">{{ $complaintCounts['closed'] }}</span>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <!-- Recent clients -->
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Clients</h5>
        <a href="{{ route('clients.index') }}" class="btn btn-sm btn-text-secondary">View all</a>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          @forelse ($recentClients as $client)
            <li class="d-flex mb-3 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded-circle bg-label-primary">
                  {{ strtoupper(substr($client->name, 0, 1)) }}
                </span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <a href="{{ route('clients.show', $client) }}" class="fw-semibold text-body d-block">
                    {{ $client->name }}
                  </a>
                  <small class="text-muted">{{ $client->company ?: $client->email }}</small>
                </div>
              </div>
            </li>
          @empty
            <li class="text-muted text-center py-3">No clients yet.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>

  <!-- Recent complaints -->
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Complaints</h5>
        <a href="{{ route('complaints.index') }}" class="btn btn-sm btn-text-secondary">View all</a>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          @php
            $statusColors = ['open' => 'danger', 'in_progress' => 'warning', 'resolved' => 'success', 'closed' => 'secondary'];
          @endphp
          @forelse ($recentComplaints as $complaint)
            <li class="d-flex mb-3 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-{{ $statusColors[$complaint->status] }}">
                  <i class="bx bx-error-circle"></i>
                </span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <a href="{{ route('complaints.show', $complaint) }}" class="fw-semibold text-body d-block">
                    {{ Str::limit($complaint->subject, 28) }}
                  </a>
                  <small class="text-muted">#{{ $complaint->ticket_number }}</small>
                </div>
                <span class="badge bg-label-{{ $statusColors[$complaint->status] }}">
                  {{ ucfirst(str_replace('_', ' ', $complaint->status)) }}
                </span>
              </div>
            </li>
          @empty
            <li class="text-muted text-center py-3">No complaints yet.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>

  <!-- Recent reports -->
  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Recent Reports</h5>
        <a href="{{ route('reports.index') }}" class="btn btn-sm btn-text-secondary">View all</a>
      </div>
      <div class="card-body">
        <ul class="p-0 m-0">
          @forelse ($recentReports as $report)
            <li class="d-flex mb-3 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                <span class="avatar-initial rounded bg-label-info"><i class="bx bx-file"></i></span>
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <a href="{{ route('reports.show', $report) }}" class="fw-semibold text-body d-block">
                    {{ Str::limit($report->title, 26) }}
                  </a>
                  <small class="text-muted">{{ $report->client->name }}</small>
                </div>
              </div>
            </li>
          @empty
            <li class="text-muted text-center py-3">No reports yet.</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection

@section('page-script')
<script>
  (function () {
    const monthLabels = @json($monthLabels);

    // Activity trend — new clients vs. complaints per month
    new ApexCharts(document.querySelector('#activityTrendChart'), {
      chart: { height: 300, type: 'area', toolbar: { show: false } },
      series: [
        { name: 'New Clients', data: @json($clientsPerMonth) },
        { name: 'Complaints', data: @json($complaintsPerMonth) },
      ],
      colors: ['#696cff', '#ff3e1d'],
      dataLabels: { enabled: false },
      stroke: { curve: 'smooth', width: 2 },
      fill: { type: 'gradient', gradient: { opacityFrom: 0.4, opacityTo: 0.1 } },
      xaxis: { categories: monthLabels },
      legend: { position: 'top', horizontalAlign: 'left' },
      grid: { borderColor: '#e7e7e7' },
    }).render();

    // Complaint status donut
    new ApexCharts(document.querySelector('#complaintStatusChart'), {
      chart: { height: 220, type: 'donut' },
      series: [
        {{ $complaintCounts['open'] }},
        {{ $complaintCounts['in_progress'] }},
        {{ $complaintCounts['resolved'] }},
        {{ $complaintCounts['closed'] }},
      ],
      labels: ['Open', 'In progress', 'Resolved', 'Closed'],
      colors: ['#ff3e1d', '#ffab00', '#71dd37', '#8592a3'],
      legend: { show: false },
      dataLabels: { enabled: false },
      plotOptions: { pie: { donut: { size: '70%' } } },
    }).render();
  })();
</script>
@endsection
