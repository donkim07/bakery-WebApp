<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'quantity', 'cost', 'size'];

    public function cakes()
    {
        return $this->belongsToMany(Cake::class, 'cake_item')->withPivot('quantity');
    }
}