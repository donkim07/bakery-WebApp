<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cake extends Model
{
    protected $fillable = ['name', 'recipe', 'price'];

    protected $casts = [
        'recipe' => 'array',
    ];

    public function items()
    {
        return $this->belongsToMany(Item::class, 'cake_item')->withPivot('quantity');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}