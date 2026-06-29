@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<div class="row">

    <div class="col-12 mb-4">

        <div class="card">

            <div class="card-body">

                <h3>

                    Welcome,

                    User Name

                </h3>

                <p class="mb-0">

                    Welcome to the Tradesmart Supplies Limited Information System Portal.

                </p>

            </div>

        </div>

    </div>

</div>

<div class="row">

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <h6>Total Clients</h6>

                <h2>{{ $totalClients ?? 0 }}</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <h6>Open Complaints</h6>

                <h2>{{ $openComplaints ?? 0 }}</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <h6>Reports Generated</h6>

                <h2>{{ $reports ?? 0 }}</h2>

            </div>

        </div>

    </div>

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <h6>Registered Users</h6>

                <h2>{{ $users ?? 0 }}</h2>

            </div>

        </div>

    </div>

</div>

<div class="row mt-4">

    <div class="col-lg-8">

        <div class="card">

            <div class="card-header">

                Complaint Trends

            </div>

            <div class="card-body">

                <div id="complaintChart"></div>

            </div>

        </div>

    </div>

    <div class="col-lg-4">

        <div class="card">

            <div class="card-header">

                Recent Activities

            </div>

            <div class="card-body">

                Recent complaints, client registrations and report generation will appear here.

            </div>

        </div>

    </div>

</div>

@endsection