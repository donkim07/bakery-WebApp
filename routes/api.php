<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\InventoryController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\StudentController;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/auth/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Stores
    Route::apiResource('stores', StoreController::class);

    // Items & Inventory
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('item-groups', ItemGroupController::class);
    Route::apiResource('items', ItemController::class);
    Route::post('/items/import', [ItemController::class, 'import']);

    // Stock Movements & Requests
    Route::apiResource('stock-movements', StockMovementController::class);
    Route::post('/stock-requests', [StockMovementController::class, 'createRequest']);
    Route::put('/stock-requests/{id}/approve', [StockMovementController::class, 'approveRequest']);
    Route::put('/stock-requests/{id}/reject', [StockMovementController::class, 'rejectRequest']);

    // Purchases
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('purchase-orders', PurchaseOrderController::class);
    Route::put('/purchase-orders/{id}/convert-to-bill', [PurchaseOrderController::class, 'convertToBill']);

    // Recipes & Manufacturing
    Route::apiResource('recipes', RecipeController::class);
    Route::post('/recipes/{id}/manufacture', [RecipeController::class, 'manufacture']);
    Route::apiResource('batches', BatchController::class);
    Route::post('/batches/{batchId}/split', [BatchController::class, 'split']);

    // Sales & Orders
    Route::apiResource('sales', SaleController::class);
    Route::get('/products', [ItemController::class, 'getProducts']);
    Route::post('/cart', [SaleController::class, 'addToCart']);
    Route::post('/checkout', [SaleController::class, 'checkout']);

    // Payments
    Route::apiResource('payments', PaymentController::class);

    // Reporting
    Route::get('/reports/sales-trends', [SaleController::class, 'salesTrends']);
    Route::get('/reports/stock-levels', [ItemController::class, 'stockLevels']);

    // Academy
    Route::apiResource('courses', CourseController::class);
    Route::get('/courses/{id}/segments', [VideoSegmentController::class, 'index']);
    Route::apiResource('enrollments', EnrollmentController::class);

    // Dashboard endpoints
    Route::prefix('dashboard')->group(function () {
        Route::get('/bakery/stats', [DashboardController::class, 'getBakeryStats']);
        Route::get('/bakery/top-products', [DashboardController::class, 'getBakeryTopProducts']);
        Route::get('/bakery/sales-chart/{period}', [DashboardController::class, 'getBakerySalesChart']);
        Route::get('/bakery/alerts', [DashboardController::class, 'getBakeryAlerts']);
        
        Route::get('/tools/stats', [DashboardController::class, 'getToolsStats']);
        Route::get('/tools/top-products', [DashboardController::class, 'getToolsTopProducts']);
        Route::get('/tools/sales-chart/{period}', [DashboardController::class, 'getToolsSalesChart']);
        Route::get('/tools/alerts', [DashboardController::class, 'getToolsAlerts']);
        
        Route::get('/academy/stats', [DashboardController::class, 'getAcademyStats']);
        Route::get('/academy/top-courses', [DashboardController::class, 'getAcademyTopCourses']);
        Route::get('/academy/enrollment-chart/{period}', [DashboardController::class, 'getAcademyEnrollmentChart']);
        Route::get('/academy/alerts', [DashboardController::class, 'getAcademyAlerts']);
    });
    
    // Product endpoints
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
        Route::get('/search/{query}', [ProductController::class, 'search']);
        Route::get('/category/{category}', [ProductController::class, 'getByCategory']);
        Route::get('/business-unit/{unit}', [ProductController::class, 'getByBusinessUnit']);
    });
    
    // Order endpoints (Tools shop)
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::put('/{id}', [OrderController::class, 'update']);
        Route::delete('/{id}', [OrderController::class, 'destroy']);
        Route::put('/{id}/status', [OrderController::class, 'updateStatus']);
    });
    
    // Sale endpoints (Bakery)
    Route::prefix('sales')->group(function () {
        Route::get('/', [SaleController::class, 'index']);
        Route::post('/', [SaleController::class, 'store']);
        Route::get('/{id}', [SaleController::class, 'show']);
        Route::put('/{id}', [SaleController::class, 'update']);
        Route::delete('/{id}', [SaleController::class, 'destroy']);
        Route::get('/report/daily', [SaleController::class, 'dailyReport']);
        Route::get('/report/weekly', [SaleController::class, 'weeklyReport']);
        Route::get('/report/monthly', [SaleController::class, 'monthlyReport']);
    });
    
    // Inventory endpoints
    Route::prefix('inventory')->group(function () {
        Route::get('/stock-level', [InventoryController::class, 'getStockLevels']);
        Route::post('/stock-adjustment', [InventoryController::class, 'adjustStock']);
        Route::post('/stock-transfer', [InventoryController::class, 'transferStock']);
        Route::get('/stock-transfers', [InventoryController::class, 'getStockTransfers']);
        Route::put('/stock-transfers/{id}/status', [InventoryController::class, 'updateTransferStatus']);
    });
    
    // Course endpoints (Academy)
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index']);
        Route::post('/', [CourseController::class, 'store']);
        Route::get('/{id}', [CourseController::class, 'show']);
        Route::put('/{id}', [CourseController::class, 'update']);
        Route::delete('/{id}', [CourseController::class, 'destroy']);
        Route::get('/category/{category}', [CourseController::class, 'getByCategory']);
    });
    
    // Student endpoints (Academy)
    Route::prefix('students')->group(function () {
        Route::get('/', [StudentController::class, 'index']);
        Route::post('/', [StudentController::class, 'store']);
        Route::get('/{id}', [StudentController::class, 'show']);
        Route::put('/{id}', [StudentController::class, 'update']);
        Route::delete('/{id}', [StudentController::class, 'destroy']);
        Route::post('/enroll', [StudentController::class, 'enroll']);
        Route::get('/{id}/courses', [StudentController::class, 'getCourses']);
    });
    
    // User management
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/', [UserController::class, 'store']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::put('/{id}/role', [UserController::class, 'updateRole']);
    });
});

// Mobile App Public Routes
Route::get('/products/public', [ItemController::class, 'getPublicProducts']);
Route::get('/tools/public', [ItemController::class, 'getPublicTools']);
Route::get('/courses/public', [CourseController::class, 'getPublicCourses']);
Route::get('/courses/{id}/segments/preview', [VideoSegmentController::class, 'getPreviewSegments']); 