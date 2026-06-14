<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioItem extends Model
{
    protected $table = 'portfolio_items';

    protected $fillable = [
        'portfolio_id',
        'type',
        'title',
        'description',
        'attachment'
    ];

    public function portfolio()
    {
        return $this->belongsTo(StudentPortfolio::class, 'portfolio_id');
    }
}
