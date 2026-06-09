<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchAdmin extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'school_id',
        'user_id',
        'assigned_at'
    ];

    public function branch()
    {
        return $this->belongsTo(SchoolBranch::class, 'school_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
