<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeePaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_id',
        'student_id',
        'gateway',
        'transaction_ref',
        'amount',
        'gateway_response',
        'status',
        'paid_at'
    ];

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
