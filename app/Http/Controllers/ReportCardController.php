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
        // 1. Get the current school term/year context
        $setting = AcademicSetting::first();
        $student = Student::findOrFail($id);

        // 2. Fetch grades based on the composite unique key we fixed [cite: 8]
        $grades = Grade::where('admission_number', $student->admission_number)
            ->where('academic_year', $setting->academic_year)
            ->where('term', $setting->current_term)
            ->get();

        // 3. Summary Calculations [cite: 10, 11]
        $totalSubjects = $grades->count();
        $studentTotal = $grades->sum('total_score');
        $obtainable = $totalSubjects * 100;
        $average = $totalSubjects > 0 ? ($studentTotal / $obtainable) * 100 : 0;
        
        // Dynamic Class Size
        $classSize = Student::where('class_level', $student->class_level)->count();

        // 4. Link Dynamic Skills (Affective/Psychomotor) [cite: 11]
        $skills = SkillRating::where('student_id', $id)->with('skill')->get();

        return view('results.report-card', compact(
            'student', 'grades', 'setting', 'totalSubjects', 
            'studentTotal', 'obtainable', 'average', 'classSize', 'skills'
        ));
    }
}