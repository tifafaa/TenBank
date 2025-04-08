<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'income',
        'outcome',
        'description',
        'status',
        'transaction_date',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
