<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeScale extends Model
{
    protected $fillable = ['grade_letter', 'min_score', 'max_score', 'remark'];
}
