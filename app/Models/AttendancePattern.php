<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendancePattern extends Model
{
    public $timestamps = false;
    protected $fillable = ['entity_type', 'entity_id', 'pattern_type', 'pattern_key', 'absence_percentage', 'total_days', 'absent_days', 'school_id'];
}
