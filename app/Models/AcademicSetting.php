<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * These must match your database column names exactly.
     */
    protected $fillable = [
        'academic_year',
        'current_term',
        'is_mid_term',
        'next_term_begins',
        'total_school_days',
    ];

    /**
     * If you want to allow EVERYTHING to be fillable (not recommended for public sites, 
     * but fine for a local school portal you manage), you can use:
     * protected $guarded = [];
     */
}