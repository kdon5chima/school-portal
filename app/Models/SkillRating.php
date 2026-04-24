<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillRating extends Model
{
   protected $fillable = ['admission_number', 'skill_id', 'rating', 'academic_year', 'term'];

public function skill() {
    return $this->belongsTo(Skill::class);
    return $this->belongsTo(Skill::class, 'skill_id');
}
}
