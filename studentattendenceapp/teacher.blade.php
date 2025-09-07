@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Teacher Dashboard</h1>
   <p>Welcome, {{ auth()->user()->name }} 👋</p>


    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Take Attendance</h5>
                    <p class="text-muted">Mark today’s student attendance</p>
                    <a href="{{ route('attendances.create') }}" class="btn btn-outline-primary">
                        <i class="fas fa-clipboard-check me-2"></i> Mark Attendance
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">Attendance Reports</h5>
                    <p class="text-muted">View and export past attendance data</p>
                    <a href="{{ route('attendances.report') }}" class="btn btn-outline-info">
                        <i class="fas fa-chart-bar me-2"></i> View Reports
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
@endsection
