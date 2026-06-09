<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'type',
        'image_url',
        'status',
        'role_visibility'
    ];

    public function getContentAttribute()
    {
        return $this->description;
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
