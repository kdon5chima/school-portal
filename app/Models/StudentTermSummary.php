<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTermSummary extends Model
{
    protected $fillable = [
    'admission_number', 
    'academic_year', 
    'term', 
    'total_school_days', 
    'days_present', 
    'days_absent',
    'teacher_comment',
    'principal_comment'
];
public function skillRatings()
{
    return $this->hasMany(SkillRating::class, 'admission_number', 'admission_number');
}
}
