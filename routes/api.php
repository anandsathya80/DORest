<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\food\FoodController;
use App\Http\Controllers\food\FoodTypeController;
use App\Http\Controllers\order\OrderController;
use App\Http\Controllers\order\OrderDetailController;
use App\Http\Controllers\order\OrderSummaryController;
use App\Http\Controllers\payment\PaymentController;
use App\Http\Controllers\sale\SaleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// food type
Route::get('/foodType', [FoodTypeController::class, 'index']);
Route::post('/foodType', [FoodTypeController::class, 'store']);


// paymnet type
Route::get('/payment', [PaymentController::class, 'index']);
Route::post('/payment', [PaymentController::class, 'store']);

// foods
Route::get('/foods', [FoodController::class, 'index']);
Route::post('/foods', [FoodController::class, 'store']);

// order
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'store']);

// summary order
Route::get('/summaryOrders/{id}', [OrderSummaryController::class, 'show']);
Route::post('/summaryOrders', [OrderSummaryController::class, 'store']);

// POS by method_payment
Route::get('/sumMethodPayment/{id}/{startDate}/{endDate}', [PaymentController::class, 'show']);

// POS by food
Route::get('/sumFood/{startDate}/{endDate}', [SaleController::class, 'countFood']);

// POS by server
Route::get('/sumServer/{id}/{startDate}/{endDate}', [SaleController::class, 'countSalesByServer']);

// POS by date interval
Route::get('/sumDate/{startDate}/{endDate}', [SaleController::class, 'countByDate']);


// order detail
Route::get('/orderDetail', [OrderDetailController::class, 'index']);
Route::post('/orderDetail', [OrderDetailController::class, 'store']);

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
