<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getRevenueReport($startDate, $endDate)
    {
        return Sale::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getExpenseReport($startDate, $endDate)
    {
        return Expense::whereBetween('date', [$startDate, $endDate])
            ->select('date', DB::raw('SUM(amount) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getTopSellingCakes($limit = 5)
    {
        return DB::table('sales')
            ->join('cakes', 'sales.cake_id', '=', 'cakes.id')
            ->select('cakes.name', DB::raw('SUM(sales.quantity) as total_sold'))
            ->groupBy('cakes.id', 'cakes.name')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();
    }

    public function getProfitReport($startDate, $endDate)
    {
        $revenue = $this->getRevenueReport($startDate, $endDate)->sum('total');
        $expenses = $this->getExpenseReport($startDate, $endDate)->sum('total');
        return $revenue - $expenses;
    }
}