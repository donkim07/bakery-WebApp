<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Expense;
use App\Models\Cake;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Sale::sum('total_price');
        $totalExpenses = Expense::sum('amount');
        $totalIncome = $totalRevenue - $totalExpenses;
        $creditBalance = Sale::where('payment_method', 'credit')->sum('total_price');

        $topCakes = Cake::withSum('sales as total_sold', 'quantity')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $lowStockItems = Item::where('quantity', '<', 10)->get();

        $last30Days = Carbon::now()->subDays(30);
        $revenueData = Sale::select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
            ->where('created_at', '>=', $last30Days)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $expenseData = Expense::select(DB::raw('DATE(date) as date'), DB::raw('SUM(amount) as total'))
            ->where('date', '>=', $last30Days)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $chartLabels = $revenueData->pluck('date');
        $revenueChartData = $revenueData->pluck('total');
        $expenseChartData = $expenseData->pluck('total');

        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalExpenses',
            'totalIncome',
            'creditBalance',
            'topCakes',
            'lowStockItems',
            'chartLabels',
            'revenueChartData',
            'expenseChartData'
        ));
    }
}