<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StudentController extends Controller
{
    public function __construct()
    {
        // Only authenticated teachers may access these methods
        $this->middleware(['auth', 'role:teacher']);
    }

    /**
     * Display a paginated list of registered students.
     */
    public function index()
    {
        $students = User::role('student')
            // Exclude any user who also has admin or teacher roles
            ->whereDoesntHave('roles', function ($q) {
                $q->whereIn('name', ['admin', 'teacher']);
            })
            ->paginate(10);

        return view('students.index', compact('students'));
    }

    /**
     * Permanently delete a student account.
     */
    public function destroy($id)
    {
        $student = User::findOrFail($id);

        if ($student->hasRole('student')) {
            $student->delete();
            return redirect()
                ->route('students.index')
                ->with('success', 'Student account deleted successfully!');
        }

        return redirect()
            ->route('students.index')
            ->with('error', 'That user is not a student.');
    }
}
