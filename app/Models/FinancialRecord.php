<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'card_number_masked',
        'balance',
        'sensitive_data',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}