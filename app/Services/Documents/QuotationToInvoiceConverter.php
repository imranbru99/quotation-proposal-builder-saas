<?php

namespace App\Services\Documents;

use App\Models\ActivityLog;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Quotation;
use Exception;
use Illuminate\Support\Facades\DB;

class QuotationToInvoiceConverter
{
    /**
     * Convert an accepted or approved quotation into a transactional invoice.
     * Guaranteed idempotent and wrapped in a database transaction.
     *
     * @param Quotation $quotation
     * @param array $overrides (e.g. custom due_date, notes)
     * @return Invoice
     * @throws Exception
     */
    public function convert(Quotation $quotation, array $overrides = []): Invoice
    {
        // 1. Idempotency & validation guard
        if ($quotation->isConverted() && $quotation->convertedInvoice) {
            return $quotation->convertedInvoice;
        }

        if (!$quotation->canBeConverted()) {
            throw new Exception("Quotation #{$quotation->quotation_number} cannot be converted in its current status ('{$quotation->status}').");
        }

        return DB::transaction(function () use ($quotation, $overrides) {
            // Re-lock quotation row for update to prevent concurrent duplicate conversion
            $lockedQuote = Quotation::where('id', $quotation->id)->lockForUpdate()->first();
            if ($lockedQuote->converted_invoice_id) {
                return Invoice::findOrFail($lockedQuote->converted_invoice_id);
            }

            // 2. Create the invoice with full financial snapshot
            $issueDate = $overrides['issue_date'] ?? now()->toDateString();
            $dueDate = $overrides['due_date'] ?? now()->addDays(14)->toDateString();

            $invoice = Invoice::create([
                'tenant_id' => $lockedQuote->tenant_id,
                'customer_id' => $lockedQuote->customer_id,
                'creator_id' => auth()->id() ?? $lockedQuote->creator_id,
                'source_quotation_id' => $lockedQuote->id,
                'title' => $lockedQuote->title ?? "Invoice for Quote #{$lockedQuote->quotation_number}",
                'status' => 'sent',
                'issue_date' => $issueDate,
                'due_date' => $dueDate,
                'currency' => $lockedQuote->currency,
                'subtotal' => $lockedQuote->subtotal,
                'discount_type' => $lockedQuote->discount_type,
                'discount_value' => $lockedQuote->discount_value,
                'discount_amount' => $lockedQuote->discount_amount,
                'tax_amount' => $lockedQuote->tax_amount,
                'grand_total' => $lockedQuote->grand_total,
                'amount_paid' => 0,
                'balance_due' => $lockedQuote->grand_total,
                'payment_terms' => $overrides['payment_terms'] ?? $lockedQuote->payment_terms,
                'notes' => $overrides['notes'] ?? $lockedQuote->notes,
                'terms_and_conditions' => $overrides['terms_and_conditions'] ?? $lockedQuote->terms_and_conditions,
                'sent_at' => now(),
            ]);

            // 3. Clone line items exactly as financial snapshot
            foreach ($lockedQuote->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item->product_id,
                    'item_name' => $item->item_name,
                    'description' => $item->description,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'unit_price' => $item->unit_price,
                    'discount_percentage' => $item->discount_percentage,
                    'tax_percentage' => $item->tax_percentage,
                    'tax_amount' => $item->tax_amount,
                    'total_price' => $item->total_price,
                    'sort_order' => $item->sort_order,
                ]);
            }

            // 4. Update the source quotation state
            $lockedQuote->update([
                'converted_invoice_id' => $invoice->id,
                'status' => 'converted',
                'converted_at' => now(),
            ]);

            // 5. Record activity log
            ActivityLog::create([
                'tenant_id' => $lockedQuote->tenant_id,
                'user_id' => auth()->id() ?? $lockedQuote->creator_id,
                'action' => 'converted',
                'subject_type' => Quotation::class,
                'subject_id' => $lockedQuote->id,
                'description' => "Quotation #{$lockedQuote->quotation_number} successfully converted to Invoice #{$invoice->invoice_number}.",
                'ip_address' => request()->ip(),
            ]);

            return $invoice;
        });
    }
}
