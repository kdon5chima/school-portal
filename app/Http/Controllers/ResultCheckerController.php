<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class ResultCheckerController extends Controller
{
    public function index() {
        return view('results.index');
    }

    public function show(Request $request) {
        $request->validate(['admission_number' => 'required']);

        // Find the student by admission number and load their grades
        $student = Student::where('admission_number', $request->admission_number)
                          ->with(['grades', 'schoolClass']) // Assumes relationships are set
                          ->first();

        if (!$student) {
            return back()->with('error', 'Student record not found.');
        }

        return view('student-result', compact('student'));
    }
}