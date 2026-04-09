<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    // This allows Filament to actually save the name to the database
    protected $fillable = [
        'name',
        'code', // Add this if you have a subject code column
    ];
}