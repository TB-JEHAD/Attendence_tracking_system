@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Student Dashboard</h1>
    <p class="text-muted">Hello {{ Auth::user()->name }} 👋</p>

    <div class="card shadow-sm border-0">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h6 class="text-muted mb-1">Your Attendance This Month</h6>
                <p>You’ve attended <strong>--</strong> out of <strong>--</strong> classes so far.</p>
                <a href="{{ route('attendances.report') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-file-alt me-2"></i> View Detailed Report
                </a>
            </div>
            <i class="fas fa-user-check fa-3x text-success"></i>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
@endsection
