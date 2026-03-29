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
        'term', 
        'subject', 
        'ca_score', 
        'exam_score', 
        'total_score', 
        'grade_letter', 
        'teacher_comment'
    ];
public function student()
{
    return $this->belongsTo(Student::class, 'admission_number', 'admission_number');
}
    /**
     * Dashboard Logic: Filters data based on the logged-in user's role.
     */
    protected static function booted()
{
    static::addGlobalScope('teacher_access', function (Builder $builder) {
        if (auth()->check() && auth()->user()->role === 'teacher') {
            // Get the list of subjects/classes assigned to this teacher
            $assignments = \App\Models\SubjectAssignment::where('user_id', auth()->id())
                ->get(['subject_name', 'class_name']);

            $builder->where(function ($query) use ($assignments) {
                foreach ($assignments as $assignment) {
                    $query->orWhere(function ($q) use ($assignment) {
                        $q->where('subject', $assignment->subject_name)
                          ->where('class_level', $assignment->class_name);
                    });
                }
            });
        }
    });
}
    public function scopeForTeacher(Builder $query): Builder
    {
        $user = auth()->user();

        // 1. If Principal (Super Admin), show EVERYTHING
        if ($user->hasRole('super_admin')) {
            return $query;
        }

        // 2. If Subject Teacher, only show their specific assigned subject
        if ($user->hasRole('subject_teacher')) {
            return $query->where('subject', $user->assigned_subject);
        }

        // 3. If Class Teacher, only show their specific class (e.g., JSS1)
        if ($user->hasRole('class_teacher')) {
            return $query->where('class_level', $user->assigned_class);
        }

        return $query;
    }

    /**
     * Auto-Calculation: Handles grading logic whenever a record is created or updated.
     */
    protected static function boot()
    {
        parent::boot();

        // This runs for both NEW records and UPDATED records
        static::saving(function ($grade) {
            $grade->total_score = $grade->ca_score + $grade->exam_score;
            
            if ($grade->total_score >= 75) $grade->grade_letter = 'A1';
            elseif ($grade->total_score >= 70) $grade->grade_letter = 'B2';
            elseif ($grade->total_score >= 65) $grade->grade_letter = 'B3';
            elseif ($grade->total_score >= 60) $grade->grade_letter = 'C4';
            elseif ($grade->total_score >= 55) $grade->grade_letter = 'C5';
            elseif ($grade->total_score >= 50) $grade->grade_letter = 'C6';
            else $grade->grade_letter = 'F9';
        });
    }
}
