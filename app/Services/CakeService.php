<?php

namespace App\Services;

use App\Models\Cake;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class CakeService
{
    public function createCake(array $data)
    {
        return DB::transaction(function () use ($data) {
            $cake = Cake::create([
                'name' => $data['name'],
                'recipe' => $data['recipe'],
                'price' => $data['price'],
            ]);

            foreach ($data['recipe'] as $itemId => $quantity) {
                $cake->items()->attach($itemId, ['quantity' => $quantity]);
            }

            return $cake;
        });
    }

    public function manufactureCake(Cake $cake)
    {
        return DB::transaction(function () use ($cake) {
            foreach ($cake->recipe as $itemId => $requiredQuantity) {
                $item = Item::findOrFail($itemId);
                if ($item->quantity < $requiredQuantity) {
                    throw new \Exception("Not enough {$item->name} to manufacture the cake.");
                }
                $item->decrement('quantity', $requiredQuantity);
            }
            return true;
        });
    }
}