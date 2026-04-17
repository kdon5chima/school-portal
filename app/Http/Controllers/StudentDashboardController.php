<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
   public function dashboard()
{
    $studentId = session('student_id');
    if (!$studentId) return redirect()->route('student.login');

    $student = \App\Models\Student::with(['schoolClass', 'grades.subject'])->findOrFail($studentId);
    $setting = \App\Models\AcademicSetting::first();

    // Logic to check if birthday is within 7 days
    $isBirthdaySoon = false;
    if ($student->date_of_birth) {
        $today = now()->setYear(2000); // Compare without considering the birth year
        $birthday = \Carbon\Carbon::parse($student->date_of_birth)->setYear(2000);
        
        // Check if birthday is between today and today + 7 days
        $isBirthdaySoon = $birthday->between($today, $today->copy()->addDays(7));
    }

    return view('student.dashboard', compact('student', 'setting', 'isBirthdaySoon'));
}
    // Show the login/checker page
    public function showChecker()
{
    // This looks for resources/views/student/login.blade.php
    return view('student.login'); 
}

    // Handle the form submission
    public function checkResult(Request $request)
{
    $request->validate([
        'admission_number' => 'required',
        'surname' => 'required',
    ]);

    // Check both admission number AND surname for security
   $student = Student::where('admission_number', $request->admission_number)
                  ->where('full_name', 'LIKE', '%' . $request->surname . '%')
                  ->first();

    if (!$student) {
        return back()->with('error', 'Invalid Admission Number or Surname.');
    }

    // Save student ID in session so they stay "logged in"
    session(['student_id' => $student->id]);

    return redirect()->route('student.dashboard');
}
}
