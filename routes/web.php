<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemGroupController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\VideoSegmentController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Welcome page redirects to login if not authenticated
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Authentication Routes (Laravel default)
Auth::routes();

// Dashboard routes - protected by 'auth' middleware
Route::middleware('auth')->group(function () {
    // Main comparison dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'main'])->name('dashboard');
    
    // Business unit specific dashboards
    Route::get('/dashboard/bakery', [App\Http\Controllers\DashboardController::class, 'bakery'])->name('dashboard.bakery');
    Route::get('/dashboard/tools', [App\Http\Controllers\DashboardController::class, 'tools'])->name('dashboard.tools');
    Route::get('/dashboard/academy', [App\Http\Controllers\DashboardController::class, 'academy'])->name('dashboard.academy');

    // Bakery Routes
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [App\Http\Controllers\ItemController::class, 'index'])->name('index');
    });
    
    Route::prefix('recipes')->name('recipes.')->group(function () {
        Route::get('/', [App\Http\Controllers\RecipeController::class, 'index'])->name('index');
    });
    
    Route::prefix('batches')->name('batches.')->group(function () {
        Route::get('/', [App\Http\Controllers\BatchController::class, 'index'])->name('index');
    });
    
    Route::prefix('stock-movements')->name('stock-movements.')->group(function () {
        Route::get('/', [App\Http\Controllers\StockMovementController::class, 'index'])->name('index');
    });
    
    Route::prefix('suppliers')->name('suppliers.')->group(function () {
        Route::get('/', [App\Http\Controllers\SupplierController::class, 'index'])->name('index');
    });
    
    Route::prefix('purchase-orders')->name('purchase-orders.')->group(function () {
        Route::get('/', [App\Http\Controllers\PurchaseOrderController::class, 'index'])->name('index');
    });
    
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [App\Http\Controllers\SaleController::class, 'index'])->name('index');
    });
    
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/', [App\Http\Controllers\ReportController::class, 'index'])->name('index');
    });
    
    // Admin only routes
    Route::middleware('can:manage-stores')->group(function () {
        Route::prefix('stores')->name('stores.')->group(function () {
            Route::get('/', [App\Http\Controllers\StoreController::class, 'index'])->name('index');
        });
    });
    
    Route::middleware('can:manage-users')->group(function () {
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('index');
        });
    });
    
    Route::middleware('can:manage-settings')->group(function () {
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [App\Http\Controllers\SettingController::class, 'index'])->name('index');
        });
    });
});

// API routes for sidebar loading
Route::prefix('api')->group(function () {
    Route::get('/sidebar/{unit}', function ($unit) {
        $validUnits = ['bakery', 'tools', 'academy'];
        if (!in_array($unit, $validUnits)) {
            return response()->json(['error' => 'Invalid unit'], 400);
        }
        
        // If sidebar component doesn't exist, fallback to bakery
        if (!view()->exists("components.sidebar.{$unit}")) {
            return response()->json(['html' => view("components.sidebar.bakery")->render()]);
        }
        
        return response()->json(['html' => view("components.sidebar.{$unit}")->render()]);
    })->middleware('auth');
});
