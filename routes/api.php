<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\Api\V1\InvoiceController;
use App\Http\Controllers\Api\V1\QuotationController;
use App\Http\Middleware\TenantMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API v1
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    // Public Authentication
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Authenticated & Tenant-Scoped Endpoints
    Route::middleware(['auth:sanctum', TenantMiddleware::class])->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Quotations
        Route::get('/quotations', [QuotationController::class, 'index']);
        Route::post('/quotations', [QuotationController::class, 'store']);
        Route::get('/quotations/{id}', [QuotationController::class, 'show']);
        Route::post('/quotations/{id}/convert-to-invoice', [QuotationController::class, 'convertToInvoice']);

        // Invoices
        Route::get('/invoices', [InvoiceController::class, 'index']);
        Route::get('/invoices/{id}', [InvoiceController::class, 'show']);

        // Customers
        Route::get('/customers', [CustomerController::class, 'index']);
        Route::post('/customers', [CustomerController::class, 'store']);
        Route::get('/customers/{id}', [CustomerController::class, 'show']);
    });
});
