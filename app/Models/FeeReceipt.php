<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeReceipt extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'receipt_no',
        'transaction_id',
        'student_id',
        'fee_id',
        'amount',
        'generated_at',
        'pdf_path'
    ];

    public function transaction()
    {
        return $this->belongsTo(FeePaymentTransaction::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }
}
