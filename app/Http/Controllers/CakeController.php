<?php

namespace App\Http\Controllers;

use App\Models\Cake;
use App\Models\Item;
use Illuminate\Http\Request;

class CakeController extends Controller
{
    public function index()
    {
        $cakes = Cake::all();
        return view('admin.cakes.index', compact('cakes'));
    }

    public function create()
    {
        $items = Item::all();
        return view('admin.cakes.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'recipe' => 'required|array',
            'recipe.*' => 'required|integer|exists:items,id',
            'recipe_quantities.*' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $cake = Cake::create([
            'name' => $validatedData['name'],
            'recipe' => array_combine($validatedData['recipe'], $validatedData['recipe_quantities']),
            'price' => $validatedData['price'],
        ]);

        return redirect()->route('cakes.index')->with('success', 'Cake created successfully');
    }

    public function edit(Cake $cake)
    {
        $items = Item::all();
        return view('admin.cakes.edit', compact('cake', 'items'));
    }

    public function update(Request $request, Cake $cake)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'recipe' => 'required|array',
            'recipe.*' => 'required|integer|exists:items,id',
            'recipe_quantities.*' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $cake->update([
            'name' => $validatedData['name'],
            'recipe' => array_combine($validatedData['recipe'], $validatedData['recipe_quantities']),
            'price' => $validatedData['price'],
        ]);

        return redirect()->route('cakes.index')->with('success', 'Cake updated successfully');
    }

    public function destroy(Cake $cake)
    {
        $cake->delete();
        return redirect()->route('cakes.index')->with('success', 'Cake deleted successfully');
    }

    public function manufacture(Cake $cake)
    {
        foreach ($cake->recipe as $itemId => $requiredQuantity) {
            $item = Item::find($itemId);
            if ($item->quantity < $requiredQuantity) {
                return redirect()->back()->with('error', "Not enough {$item->name} to manufacture the cake.");
            }
        }

        foreach ($cake->recipe as $itemId => $requiredQuantity) {
            $item = Item::find($itemId);
            $item->decrement('quantity', $requiredQuantity);
        }

        return redirect()->back()->with('success', 'Cake manufactured successfully');
    }
}