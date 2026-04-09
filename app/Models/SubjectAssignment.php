<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectAssignment extends Model
{
   // app/Models/SubjectAssignment.php
protected $fillable = [
    'user_id', // The Teacher
    'subject_id',
    'school_class_id', // Make sure this is nullable in your migration!
    'academic_session_id',
];
}
