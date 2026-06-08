<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimetableVersion extends Model
{
    protected $fillable = [
        'name',
        'status',
        'academic_year_id',
        'created_by',
        'approved_by',
        'approved_at',
        'published_by',
        'published_at'
    ];

    public function timetables()
    {
        return $this->hasMany(Timetable::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function publishedBy()
    {
        return $this->belongsTo(User::class, 'published_by');
    }
}
