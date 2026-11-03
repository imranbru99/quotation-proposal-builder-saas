<?php

use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\PublicWebsiteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public SaaS Website & Marketing Pages
|--------------------------------------------------------------------------
*/
Route::get('/', [PublicWebsiteController::class, 'home'])->name('public.home');
Route::get('/modules', [PublicWebsiteController::class, 'modules'])->name('public.modules');
Route::get('/modules/{id}', [PublicWebsiteController::class, 'moduleDetail'])->name('public.module.detail');
Route::get('/pricing', [PublicWebsiteController::class, 'pricing'])->name('public.pricing');
Route::get('/solutions', [PublicWebsiteController::class, 'solutions'])->name('public.solutions');
Route::get('/ai-features', [PublicWebsiteController::class, 'aiFeatures'])->name('public.ai');
Route::get('/security', [PublicWebsiteController::class, 'security'])->name('public.security');
Route::get('/docs', [PublicWebsiteController::class, 'documentation'])->name('public.docs');
Route::get('/contact', [PublicWebsiteController::class, 'contact'])->name('public.contact');
Route::post('/contact', [PublicWebsiteController::class, 'submitContact'])->name('public.contact.submit');

// 1-Click Interactive Demo Role Switcher
Route::get('/demo/login/{role}', [PublicWebsiteController::class, 'launchDemoRole'])->name('public.demo.login');

// Convenient redirects for Filament
Route::get('/login', fn() => redirect('/app/login'))->name('login');

/*
|--------------------------------------------------------------------------
| Customer Client Portal (Secure Tokenized Access)
|--------------------------------------------------------------------------
*/
Route::prefix('portal')->group(function () {
    // Quotations
    Route::get('/quote/{token}', [CustomerPortalController::class, 'viewQuotation'])->name('portal.quote.view');
    Route::post('/quote/{token}/accept', [CustomerPortalController::class, 'acceptQuotation'])->name('portal.quote.accept');
    Route::post('/quote/{token}/reject', [CustomerPortalController::class, 'rejectQuotation'])->name('portal.quote.reject');
    Route::get('/quote/{token}/pdf', [CustomerPortalController::class, 'downloadQuotationPdf'])->name('portal.quote.pdf');

    // Proposals & E-Signatures
    Route::get('/proposal/{token}', [CustomerPortalController::class, 'viewProposal'])->name('portal.proposal.view');
    Route::get('/proposal/{token}/pdf', [CustomerPortalController::class, 'downloadProposalPdf'])->name('portal.proposal.pdf');
    Route::get('/sign/{signToken}', [CustomerPortalController::class, 'showSigningPage'])->name('portal.sign.show');
    Route::post('/sign/{signToken}', [CustomerPortalController::class, 'executeSignature'])->name('portal.sign.execute');

    // Invoices & Receipts
    Route::get('/invoice/{token}', [CustomerPortalController::class, 'viewInvoice'])->name('portal.invoice.view');
    Route::get('/invoice/{token}/pdf', [CustomerPortalController::class, 'downloadInvoicePdf'])->name('portal.invoice.pdf');
    Route::get('/receipt/{receiptNumber}', [CustomerPortalController::class, 'viewReceipt'])->name('portal.receipt.view');
});
