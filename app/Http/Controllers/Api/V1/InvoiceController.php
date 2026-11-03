<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    public function index(): JsonResponse
    {
        $invoices = Invoice::with(['customer', 'items', 'payments'])
            ->latest()
            ->paginate(15);

        return response()->json($invoices);
    }

    public function show(int $id): JsonResponse
    {
        $invoice = Invoice::with(['customer', 'items', 'payments', 'sourceQuotation'])->findOrFail($id);
        return response()->json($invoice);
    }
}
