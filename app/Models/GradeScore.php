<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeScore extends Model
{
    protected $fillable = [
        'admission_number',
        'student_name',
        'class_level',
        'subject',
        'term',
        'academic_year',
        'ca_score',
        'exam_score',
        'total_score',
        'grade_letter',
    ];

    // Optional: Relationship to student
    public function student()
    {
        return $this->belongsTo(Student::class, 'admission_number', 'admission_number');
    }
}