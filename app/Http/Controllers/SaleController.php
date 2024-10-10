<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Cake;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['cake', 'user'])->get();
        return view('admin.sales.index', compact('sales'));
    }

    public function create()
    {
        $cakes = Cake::all();
        return view('admin.sales.create', compact('cakes'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cake_id' => 'required|exists:cakes,id',
            'quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:cash,credit',
        ]);

        $cake = Cake::findOrFail($validatedData['cake_id']);
        $total_price = $cake->price * $validatedData['quantity'];

        Sale::create([
            'cake_id' => $validatedData['cake_id'],
            'quantity' => $validatedData['quantity'],
            'total_price' => $total_price,
            'payment_method' => $validatedData['payment_method'],
            'user_id' => auth()->id,
        ]);

        return redirect()->route('sales.index')->with('success', 'Sale recorded successfully');
    }

    public function show(Sale $sale)
    {
        return view('admin.sales.show', compact('sale'));
    }
}