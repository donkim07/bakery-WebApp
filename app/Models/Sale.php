<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = ['cake_id', 'quantity', 'total_price', 'payment_method', 'user_id'];

    public function cake()
    {
        return $this->belongsTo(Cake::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}