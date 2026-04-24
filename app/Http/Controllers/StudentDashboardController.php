<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Announcement;
use App\Models\AcademicSetting;
use App\Models\Subject; // 1. IMPORT THE SUBJECT MODEL
use Illuminate\Http\Request;
use Carbon\Carbon;

class StudentDashboardController extends Controller
{
    public function dashboard()
    {
        $studentId = session('student_id');
        if (!$studentId) {
            return redirect()->route('student.login');
        }

        /** * We remove 'schoolClass' and 'grades.subject' because your 
         * tables use 'class_level' and 'subject' as plain text columns.
         */
        $student = Student::with(['grades'])->findOrFail($studentId);
        $setting = AcademicSetting::first();
        
        // 2. FETCH THE SUBJECTS
        // This pulls all subjects from your 'subjects' table with columns 'name' and 'code'
        $subjects = Subject::orderBy('name', 'asc')->get();

        // Fetch active announcements to display on the dashboard
        $announcements = Announcement::where('is_active', true)
                            ->latest()
                            ->take(3)
                            ->get();

        // Birthday logic
        $isBirthdaySoon = false;
        if ($student->date_of_birth) {
            $today = now()->setYear(2000)->startOfDay(); 
            $birthday = Carbon::parse($student->date_of_birth)->setYear(2000)->startOfDay();
            
            // Check if birthday is within the next 7 days
            $isBirthdaySoon = $birthday->between($today, $today->copy()->addDays(7));
        }

        // 3. ADD 'subjects' TO THE COMPACT ARRAY
        return view('student.dashboard', compact(
            'student', 
            'setting', 
            'announcements', 
            'isBirthdaySoon', 
            'subjects'
        ));
    }

    public function showChecker()
    {
        return view('student.login'); 
    }

    public function checkResult(Request $request)
    {
        $request->validate([
            'admission_number' => 'required',
            'surname' => 'required',
        ]);

        // Matches surname against your 'full_name' column using wildcards
        $student = Student::where('admission_number', $request->admission_number)
                          ->where('full_name', 'LIKE', '%' . $request->surname . '%')
                          ->first();

        if (!$student) {
            return back()->with('error', 'Invalid Admission Number or Surname.');
        }

        session(['student_id' => $student->id]);

        return redirect()->route('student.dashboard');
    }
    
    public function logout()
    {
        session()->forget('student_id');
        return redirect()->route('student.login');
    }
}