<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolEvent extends Model
{
    protected $table = 'events';
    protected $fillable = ['title', 'description', 'start_date', 'end_date', 'status', 'role_visibility', 'school_id'];
    // Assuming table has these based on earlier hints in migration names
}
