<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Skill extends Model
{
    /**
     * The attributes that are mass assignable.
     * * name: e.g., 'Punctuality', 'Honesty', 'Handwriting'
     * type: e.g., 'Affective', 'Psychomotor'
     */
    protected $fillable = ['name', 'type'];

    /**
     * Relationship to Skill Ratings.
     * This allows you to see all student ratings for this specific skill.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(SkillRating::class, 'skill_id');
    }
}