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
        'status', // Removed the duplicate 'status' entry here
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
     */
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Security Scope: Filters students based on User Role.
     */
    public function scopeForTeacher(Builder $query): Builder
    {
        $user = auth()->user();

        // Safety check: If no user is logged in (e.g., Public Result Checker),
        // we don't apply the teacher filter.
        if (!$user) {
            return $query;
        }

        // Data Analyst/Super Admin sees all students
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return $query;
        }

        // Teachers only see students assigned to their class
        return $query->where('school_class_id', $user->school_class_id);
    }
}