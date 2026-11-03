<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Documents\QuotationToInvoiceConverter;
use App\Services\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class QuotationWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_quotation_calculations_and_1_click_invoice_conversion(): void
    {
        $plan = Plan::create(['name' => 'Growth', 'slug' => 'growth', 'price_monthly' => 30, 'is_active' => true]);
        $tenant = Tenant::create(['plan_id' => $plan->id, 'name' => 'Design Studio', 'slug' => 'design-studio', 'currency' => 'USD']);
        app(TenantContext::class)->setTenant($tenant);

        $user = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Sales Agent',
            'email' => 'agent@studio.test',
            'password' => 'secret',
            'role' => 'sales_executive',
        ]);

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Michael Scott',
            'company_name' => 'Dunder Mifflin',
            'email' => 'michael@dunder.test',
        ]);

        $product = Product::create([
            'tenant_id' => $tenant->id,
            'name' => 'Brand Strategy Package',
            'price' => 2000.00,
            'cost' => 500.00,
        ]);

        // Create Quotation
        $quote = Quotation::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'creator_id' => $user->id,
            'title' => 'Brand Strategy & Identity',
            'status' => 'draft',
            'issue_date' => now(),
            'currency' => 'USD',
            'discount_type' => 'percentage',
            'discount_value' => 10.00, // 10% discount
        ]);

        QuotationItem::create([
            'quotation_id' => $quote->id,
            'product_id' => $product->id,
            'item_name' => $product->name,
            'quantity' => 2,
            'unit_price' => 2000.00, // 2 * 2000 = 4000
            'tax_percentage' => 5.00, // 5% tax = 200
            'tax_amount' => 200.00,
            'total_price' => 4200.00,
        ]);

        $quote->calculateTotals();

        // Subtotal = 4000, 10% discount = 400, Tax = 200 => Grand total = 3800
        $this->assertEquals(4000.00, (float) $quote->subtotal);
        $this->assertEquals(400.00, (float) $quote->discount_amount);
        $this->assertEquals(200.00, (float) $quote->tax_amount);
        $this->assertEquals(3800.00, (float) $quote->grand_total);

        // Mark Accepted
        $quote->update(['status' => 'accepted']);
        $this->assertTrue($quote->canBeConverted());

        // Convert to Invoice
        $converter = app(QuotationToInvoiceConverter::class);
        $invoice = $converter->convert($quote);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals(3800.00, (float) $invoice->grand_total);
        $this->assertEquals(3800.00, (float) $invoice->balance_due);
        $this->assertEquals($customer->id, $invoice->customer_id);
        $this->assertEquals($quote->id, $invoice->source_quotation_id);

        // Verify quote status changed to converted
        $quote->refresh();
        $this->assertEquals('converted', $quote->status);
        $this->assertEquals($invoice->id, $quote->converted_invoice_id);

        // Idempotency check: Converting again must NOT create a new invoice
        $secondInvoice = $converter->convert($quote);
        $this->assertEquals($invoice->id, $secondInvoice->id);
        $this->assertEquals(1, Invoice::count());
    }
}

