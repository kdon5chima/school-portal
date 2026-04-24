<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Grade;
use App\Models\AcademicSetting;
use App\Models\SkillRating;
use Illuminate\Http\Request;

class ReportCardController extends Controller
{
    public function generate($id)
    {
        // 1. Get the basics
        $student = Student::findOrFail($id);
        $setting = AcademicSetting::first();

        /**
         * 2. Fetch Grades with Subject Codes
         * We use trim() logic implicitly by ensuring the join is clean.
         * We also ensure the admission_number matches the student exactly.
         */
        $grades = Grade::where('admission_number', $student->admission_number)
            ->leftJoin('subjects', 'grades.subject', '=', 'subjects.name')
            ->select([
                'grades.ca_score', 
                'grades.exam_score', 
                'grades.total_score', 
                'grades.subject', // The name from grades table
                'subjects.code as subject_code' // The code from subjects table
            ])
            ->get();

        // 3. Perform Calculations
        $totalSubjects = $grades->count();
        $studentTotal = $grades->sum('total_score');
        $obtainable = $totalSubjects * 100;
        $average = $totalSubjects > 0 ? ($studentTotal / $obtainable) * 100 : 0;
        
        // GPA calculation (Scale of 5.0)
        $gpa = $totalSubjects > 0 ? ($average / 100) * 5 : 0; 

        // 4. Class size and Skills
        $classSize = Student::where('class_level', $student->class_level)->count();
        
        $skills = SkillRating::where('admission_number', $student->admission_number)
            ->with('skill')
            ->get();

        // 5. Send everything to the view
        return view('results.report-card', compact(
            'student', 
            'grades', 
            'setting', 
            'totalSubjects', 
            'studentTotal', 
            'obtainable', 
            'average', 
            'classSize', 
            'skills', 
            'gpa'
        ));
    }
}