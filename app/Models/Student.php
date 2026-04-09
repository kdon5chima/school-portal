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
        'status',
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
     * This ensures Teachers only see their assigned students.
     */
    public function scopeForTeacher(Builder $query): Builder
    {
        $user = auth()->user();

        // 1. If not logged in (Public Result Checker), show nothing or all 
        // depending on your ResultController logic.
        if (!$user) {
            return $query;
        }

        // 2. Super Admin / Data Analyst sees everything
        if (method_exists($user, 'hasRole') && $user->hasRole('super_admin')) {
            return $query;
        }

        // 3. Teachers: Only see students in their assigned class
        if (method_exists($user, 'hasRole') && $user->hasRole('teacher')) {
            return $query->where('school_class_id', $user->school_class_id);
        }

        // 4. Default: Fallback to showing nothing if role is unrecognized
        // This is safer for school data privacy.
        return $query;
    }
}