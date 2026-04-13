<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectAssignment extends Model
{
    protected $fillable = [
        'user_id',
        'subject_id', 
        'school_class_id',
        'academic_session_id',
    ];

    /**
     * The attributes that should be cast.
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
     * * FIXED: We use 'subject_id' as the foreign key. 
     * The second parameter should be the local column (subject_id).
     * The third parameter is the ID on the Subjects table (usually 'id').
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    /**
     * Link to the Class
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'school_class_id');
    }
}