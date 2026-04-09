<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectAssignment extends Model
{
    protected $fillable = [
        'user_id',
        'subject_id', // Stores single ID (Specialist) or Array (Form Teacher)
        'school_class_id',
        'academic_session_id',
    ];

    /**
     * The attributes that should be cast.
     * This handles the JSON conversion for the CheckboxList automatically.
     */
    protected $casts = [
        'subject_id' => 'array',
    ];

    /**
     * Link to the Teacher (User model)
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Link to the Subject
     * Note: This works when subject_id contains a single value.
     */
    public function subject(): BelongsTo
    {
        // Fixed: Use 'subject_id' as the foreign key, not 'subject_name'
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }

    /**
     * Link to the Class
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }
}