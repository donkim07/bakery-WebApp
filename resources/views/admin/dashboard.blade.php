@extends('layout.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="text-3xl font-semibold text-gray-900">Dashboard</h1>

<div class="mt-4">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Revenue Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Revenue
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                ${{ number_format($totalRevenue, 2) }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Income Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Income
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                ${{ number_format($totalIncome, 2) }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Expenses Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Expenses
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                ${{ number_format($totalExpenses, 2) }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Credit Balance Card -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Credit Balance
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                ${{ number_format($creditBalance, 2) }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
        <!-- Revenue and Income Chart -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Revenue and Income</h3>
                <div class="mt-2 h-64">
                    <canvas id="revenueIncomeChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Selling Cakes -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Top Selling Cakes</h3>
                <div class="mt-2">
                    <ul class="divide-y divide-gray-200">
                        @foreach ($topCakes as $cake)
                            <li class="py-3 flex justify-between items-center">
                                <div class="flex items-center">
                                    <span class="text-gray-900">{{ $cake->name }}</span>
                                </div>
                                <div class="text-gray-500">
                                    {{ $cake->total_sold }} sold
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueIncomeChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [{
                label: 'Revenue',
                data: @json($revenueChartData),
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: 'Expenses',
                data: @json($expenseChartData),
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endpush