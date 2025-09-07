@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h1 class="mb-4">📋 Registered Students</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if($students->isEmpty())
        <p>No students found.</p>
    @else
        <table class="table table-striped table-bordered">
            <thead class="thead-light">
                <tr>
                    <th style="width: 5%;">ID</th>
                    <th style="width: 40%;">Name</th>
                    <th style="width: 40%;">Email</th>
                    <th style="width: 15%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>
                            <form action="{{ route('students.destroy', $student->id) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this student permanently?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    🗑 Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $students->links() }}
        </div>
    @endif

</div>
@endsection
