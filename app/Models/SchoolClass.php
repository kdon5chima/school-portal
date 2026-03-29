<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
  protected $fillable = [
    'name', 
    'arm', 
    'section'
];
}
