@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Edit Attendance for {{ $attendance->student->name }} ({{ $attendance->date }})</h2>

    <form method="POST" action="{{ route('attendances.update', $attendance->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group mt-3">
            <label>Status:</label>
            <select name="status" class="form-control">
                <option value="present" {{ $attendance->status == 'present' ? 'selected' : '' }}>Present</option>
                <option value="absent" {{ $attendance->status == 'absent' ? 'selected' : '' }}>Absent</option>
                <option value="late" {{ $attendance->status == 'late' ? 'selected' : '' }}>Late</option>
            </select>
        </div>

        <div class="form-group mt-3">
            <label>Remarks:</label>
            <input type="text" name="remarks" class="form-control" value="{{ $attendance->remarks }}">
        </div>

        <button class="btn btn-primary mt-3">Update</button>
    </form>
</div>
@endsection
