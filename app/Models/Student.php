<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 
        'admission_number', 
        'school_class_id', 
        'gender', 
        'class_level', 
        'date_of_birth',
        'parent_email',
        'student_email', // Added based on your DB structure
        'status',
        'student_image',
    ];

    /**
     * Relationship to the Class/Arm.
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }

    /**
     * Relationship to the Academic Grades.
     * Fixed: Using admission_number as the bridge between tables.
     */
    public function grades(): HasMany
    {
        // We only need this one line. It links the 'admission_number' 
        // in the grades table to the 'admission_number' in this table.
        return $this->hasMany(Grade::class, 'admission_number', 'admission_number');
    }

    /**
     * Security Scope: Filters students based on User Role.
     */
    public function scopeForTeacher(Builder $query): Builder
    {
        $user = auth()->user();

        if (!$user) {
            return $query;
        }

        // Super Admin / Data Analyst sees everything
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return $query;
        }

        // Teachers: Only see students in their assigned class
        if (method_exists($user, 'hasRole') && $user->hasRole('teacher')) {
            return $query->where('school_class_id', $user->school_class_id);
        }

        return $query;
    }
}