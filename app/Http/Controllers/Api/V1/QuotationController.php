<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Services\Documents\QuotationToInvoiceConverter;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QuotationController extends Controller
{
    public function __construct(
        protected QuotationToInvoiceConverter $converter
    ) {}

    public function index(): JsonResponse
    {
        $quotations = Quotation::with(['customer', 'items'])
            ->latest()
            ->paginate(15);

        return response()->json($quotations);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'title' => 'nullable|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:issue_date',
            'currency' => 'nullable|string|size:3',
            'discount_type' => 'nullable|in:percentage,fixed',
            'discount_value' => 'nullable|numeric|min:0',
            'payment_terms' => 'nullable|string',
            'delivery_terms' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.description' => 'nullable|string',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        $quotation = DB::transaction(function () use ($validated, $request) {
            $user = $request->user();
            $tenant = $user->tenant;

            $currency = $validated['currency'] ?? ($tenant?->currency ?? 'USD');

            $quote = Quotation::create([
                'tenant_id' => $user->tenant_id,
                'customer_id' => $validated['customer_id'],
                'creator_id' => $user->id,
                'title' => $validated['title'] ?? 'Quotation',
                'status' => 'draft',
                'issue_date' => $validated['issue_date'],
                'expiry_date' => $validated['expiry_date'] ?? null,
                'currency' => $currency,
                'discount_type' => $validated['discount_type'] ?? 'percentage',
                'discount_value' => $validated['discount_value'] ?? 0,
                'payment_terms' => $validated['payment_terms'] ?? null,
                'delivery_terms' => $validated['delivery_terms'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $subtotal = 0;
            $taxAmount = 0;

            foreach ($validated['items'] as $index => $itemData) {
                $qty = (float) $itemData['quantity'];
                $price = (float) $itemData['unit_price'];
                $taxPercent = (float) ($itemData['tax_percentage'] ?? 0);

                $itemSubtotal = $qty * $price;
                $itemTax = $itemSubtotal * ($taxPercent / 100);
                $totalPrice = $itemSubtotal + $itemTax;

                $subtotal += $itemSubtotal;
                $taxAmount += $itemTax;

                QuotationItem::create([
                    'quotation_id' => $quote->id,
                    'item_name' => $itemData['item_name'],
                    'description' => $itemData['description'] ?? null,
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'tax_percentage' => $taxPercent,
                    'tax_amount' => $itemTax,
                    'total_price' => $totalPrice,
                    'sort_order' => $index,
                ]);
            }

            $discountAmount = 0;
            if ($quote->discount_type === 'percentage') {
                $discountAmount = $subtotal * (((float) $quote->discount_value) / 100);
            } else {
                $discountAmount = min($subtotal, (float) $quote->discount_value);
            }

            $quote->subtotal = $subtotal;
            $quote->discount_amount = $discountAmount;
            $quote->tax_amount = $taxAmount;
            $quote->grand_total = max(0, ($subtotal - $discountAmount + $taxAmount));
            $quote->save();

            return $quote;
        });

        return response()->json([
            'message' => 'Quotation created successfully',
            'quotation' => $quotation->load(['customer', 'items']),
            'review_url' => route('portal.quote.view', ['token' => $quotation->view_token]),
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $quotation = Quotation::with(['customer', 'items', 'convertedInvoice'])->findOrFail($id);
        return response()->json($quotation);
    }

    public function convertToInvoice(Request $request, int $id): JsonResponse
    {
        $quotation = Quotation::findOrFail($id);

        try {
            $invoice = $this->converter->convert($quotation, [
                'due_date' => $request->due_date,
                'notes' => $request->notes,
            ]);

            return response()->json([
                'message' => "Quotation #{$quotation->quotation_number} converted into Invoice #{$invoice->invoice_number} successfully.",
                'invoice' => $invoice->load(['customer', 'items']),
                'invoice_url' => route('portal.invoice.view', ['token' => $invoice->view_token]),
            ]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }
}

