<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Complaint;
use App\Models\Report;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalClients = Client::count();
        $activeClients = Client::where('status', 'active')->count();
        $totalReports = Report::count();

        $complaintCounts = [
            'open' => Complaint::where('status', 'open')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'closed' => Complaint::where('status', 'closed')->count(),
        ];

        // Clients added per month, last 6 months — for the trend chart.
        $months = collect(range(5, 0))->map(fn ($i) => now()->subMonths($i));
        $clientsPerMonth = $months->map(function ($month) {
            return Client::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        });
        $complaintsPerMonth = $months->map(function ($month) {
            return Complaint::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        });

        $recentClients = Client::latest()->take(5)->get();
        $recentComplaints = Complaint::with('client')->latest()->take(5)->get();
        $recentReports = Report::with(['client', 'uploader'])->latest()->take(5)->get();

        $avgResolutionDays = Complaint::whereNotNull('resolved_at')
            ->get()
            ->avg(fn ($c) => $c->created_at->diffInDays($c->resolved_at));

        return view('dashboard', [
            'totalClients' => $totalClients,
            'activeClients' => $activeClients,
            'totalReports' => $totalReports,
            'complaintCounts' => $complaintCounts,
            'openComplaints' => $complaintCounts['open'] + $complaintCounts['in_progress'],
            'monthLabels' => $months->map(fn ($m) => $m->format('M')),
            'clientsPerMonth' => $clientsPerMonth,
            'complaintsPerMonth' => $complaintsPerMonth,
            'recentClients' => $recentClients,
            'recentComplaints' => $recentComplaints,
            'recentReports' => $recentReports,
            'avgResolutionDays' => round($avgResolutionDays ?? 0, 1),
        ]);
    }
}
