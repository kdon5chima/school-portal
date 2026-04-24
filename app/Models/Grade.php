<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Grade extends Model
{
    protected $fillable = [
        'student_name', 
        'admission_number', 
        'class_level', 
        'academic_year', // Added to ensure year-to-year tracking
        'term', 
        'subject', 
        'ca_score', 
        'exam_score', 
        'total_score', 
              
    ];

    /**
     * Relationship to the Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'admission_number', 'admission_number');
    }

    /**
     * Global Scope: Teacher Privacy & Access
     * Automatically filters results so teachers only see the subjects and 
     * classes assigned to them in the SubjectAssignment table.
     */
    protected static function booted()
{
    static::saving(function ($grade) {
        // 1. Calculate the total
        $grade->total_score = (float)$grade->ca_score + (float)$grade->exam_score;

        // 2. Lookup the range in your grade_scales table
        $scale = \App\Models\GradeScale::where('min_score', '<=', $grade->total_score)
            ->where('max_score', '>=', $grade->total_score)
            ->first();

        // 3. Assign the letter and remark automatically
        if ($scale) {
            $grade->grade_letter = $scale->grade_letter;
            $grade->teacher_comment = $scale->remark;
        } else {
            $grade->grade_letter = 'N/A';
        }
    });
}
    /**
     * Flexible Logic Boot:
     * 1. Calculates Total Score automatically.
     * 2. Dynamically fetches Grade Letter from the GradeScale table.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($grade) {
            // 1. Calculate the Total
            $grade->total_score = ($grade->ca_score ?? 0) + ($grade->exam_score ?? 0);
            
            // 2. Flexible Grading Logic
            // We query the GradeScale table to see where this total fits.
            // This allows you to change grading rules in the UI without touching code.
            $scale = \App\Models\GradeScale::where('min_score', '<=', $grade->total_score)
                ->where('max_score', '>=', $grade->total_score)
                ->first();

            $grade->grade_letter = $scale ? $scale->grade_letter : 'N/A';
        });
    }
}