@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Attendance Records</h1>
    <a href="{{ route('attendances.create', ['date' => $date]) }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Take Attendance
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('attendances.index') }}" method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <input type="date" class="form-control" name="date" value="{{ $date }}" onchange="this.form.submit()">
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Roll Number</th>
                        <th>Class</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        @role('admin')
                        <th>Actions</th>
                        @endrole
                    </tr>
                </thead>
                <tbody>
                    @forelse($attendances as $attendance)
                    <tr>
                        <td>{{ $attendance->student->name }}</td>
                        <td>{{ $attendance->student->roll_number }}</td>
                        <td>{{ $attendance->student->class }}</td>
                        <td>
                            <span class="badge bg-{{ $attendance->status === 'present' ? 'success' : ($attendance->status === 'late' ? 'warning' : 'danger') }}">
                                {{ ucfirst($attendance->status) }}
                            </span>
                        </td>
                        <td>{{ $attendance->remarks }}</td>

                        @role('admin')
                        <td>
                            <a href="{{ route('attendances.edit', $attendance->id) }}" class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>
                        </td>
                        @endrole
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">No attendance records found for this date.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
