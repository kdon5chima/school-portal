<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Remark extends Model
{
    // Add this line inside the class
    protected $fillable = [
        'content',
        'type', // I'm adding 'type' as well since you use 'Teacher' or 'Principal'
    ];
}