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
        'grade_letter', 
        
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
        static::addGlobalScope('teacher_access', function (Builder $builder) {
            if (auth()->check() && auth()->user()->role === 'teacher') {
                
                $assignments = \App\Models\SubjectAssignment::where('user_id', auth()->id())
                    ->with(['subject', 'schoolClass']) 
                    ->get();

                $builder->where(function ($query) use ($assignments) {
                    foreach ($assignments as $assignment) {
                        $subjectName = $assignment->subject?->name;
                        $className = $assignment->schoolClass?->name;

                        if ($subjectName && $className) {
                            $query->orWhere(function ($q) use ($subjectName, $className) {
                                $q->where('subject', $subjectName)
                                  ->where('class_level', $className);
                            });
                        }
                    }
                });
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