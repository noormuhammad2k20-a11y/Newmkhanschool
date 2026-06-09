<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolNotification extends Model
{
    protected $table = 'notifications';
    protected $fillable = ['user_id', 'title', 'message', 'is_read', 'school_id'];
    
    public function user() { return $this->belongsTo(User::class); }
}
