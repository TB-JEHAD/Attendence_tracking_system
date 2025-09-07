@extends('layouts.app')

@section('content')
@php use Carbon\Carbon; @endphp

<style>
    body {
        background: linear-gradient(145deg, #eef1f8, #f8fafc);
        font-family: 'Inter', sans-serif;
    }

    .page-header {
        font-size: 2.5rem;
        font-weight: 700;
        text-align: center;
        color: #1e293b;
        margin-bottom: 2rem;
    }

    .glass-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.7);
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }

    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.08);
    }

    .glass-card .card-body {
        padding: 1.5rem 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .student-info {
        display: flex;
        flex-direction: column;
    }

    .student-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #0f172a;
    }

    .student-id {
        font-size: 0.875rem;
        color: #64748b;
    }

    .form-select {
        border-radius: 12px;
        padding: 0.5rem 1rem;
        font-size: 0.95rem;
        border: 1px solid #cbd5e1;
        background-color: #f1f5f9;
        transition: border 0.3s;
    }

    .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.15rem rgba(59, 130, 246, 0.25);
    }

    .btn {
        border-radius: 10px;
        font-weight: 500;
        padding: 0.5rem 1.5rem;
        transition: all 0.3s ease-in-out;
    }

    .btn-success:hover {
        background-color: #22c55e;
        box-shadow: 0 8px 20px rgba(34, 197, 94, 0.3);
    }

    .btn-outline-danger:hover {
        background-color: #dc2626;
        color: #fff;
        border-color: #dc2626;
    }

    .btn-warning:hover {
        background-color: #eab308;
        color: #fff;
    }

    .alert {
        border-radius: 12px;
        font-size: 1rem;
        text-align: center;
    }

    .locked-note {
        font-size: 0.9rem;
        color: #dc2626;
        text-align: center;
        font-weight: 500;
        margin-top: 1.5rem;
    }

    @media (max-width: 576px) {
        .glass-card .card-body {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
    }
</style>

<div class="container py-5">
    <h2 class="page-header">
        Take Attendance — {{ Carbon::parse($date)->format('l, F j, Y') }}
    </h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('attendances.store') }}">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">

        @foreach($students as $s)
            <div class="glass-card mb-3">
                <div class="card-body">
                    <div class="student-info">
                        <div class="student-name">{{ $s->name }}</div>
                        <div class="student-id">ID: {{ $s->id }}</div>
                    </div>
                    <div>
                        <select
                            name="attendance[{{ $s->id }}][status]"
                            class="form-select"
                            required
                            {{ (!empty($statusMap) && !$canEdit) ? 'disabled' : '' }}
                        >
                            <option value="">Select status</option>
                            <option value="present" {{ ($statusMap[$s->id] ?? '')==='present' ? 'selected' : '' }}>
                                Present
                            </option>
                            <option value="absent" {{ ($statusMap[$s->id] ?? '')==='absent' ? 'selected' : '' }}>
                                Absent
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2">
            <a
                href="{{ route('attendances.cancel', ['date' => $date]) }}"
                class="btn btn-outline-danger"
                onclick="return confirm('Cancel attendance for {{ $date }}?')"
            >Cancel</a>

            <div class="d-flex gap-2">
                @if($canEdit)
                    <a
                        href="{{ route('attendances.edit', ['date' => $date]) }}"
                        class="btn btn-warning"
                    >Edit</a>
                @endif

                <button
                    type="submit"
                    class="btn btn-success"
                    {{ (!empty($statusMap) && !$canEdit) ? 'disabled' : '' }}
                >
                    {{ empty($statusMap) ? 'Save' : 'Update' }}
                </button>
            </div>
        </div>

        @if(!empty($statusMap) && !$canEdit)
            <p class="locked-note">
                Attendance editing is no longer allowed for this date.
            </p>
        @endif
    </form>
</div>
@endsection
