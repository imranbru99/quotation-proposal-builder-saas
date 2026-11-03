<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Tenant;
use App\Services\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvoicePaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_payment_reconciliation_and_status_transitions(): void
    {
        $plan = Plan::create(['name' => 'Starter', 'slug' => 'starter', 'is_active' => true]);
        $tenant = Tenant::create(['plan_id' => $plan->id, 'name' => 'Accounting Plus', 'slug' => 'accounting-plus']);
        app(TenantContext::class)->setTenant($tenant);

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Robert Ford',
            'company_name' => 'Delos Destinations',
            'email' => 'robert@delos.test',
        ]);

        $invoice = Invoice::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'title' => 'Quarterly Retainer',
            'status' => 'sent',
            'issue_date' => now(),
            'due_date' => now()->addDays(14),
            'currency' => 'USD',
            'subtotal' => 1000.00,
            'grand_total' => 1000.00,
            'amount_paid' => 0.00,
            'balance_due' => 1000.00,
        ]);

        // 1. Record Partial Payment: 400 USD
        Payment::create([
            'tenant_id' => $tenant->id,
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'amount' => 400.00,
            'currency' => 'USD',
            'payment_date' => now(),
            'payment_method' => 'bank_transfer',
        ]);

        $invoice->refresh();
        $this->assertEquals(400.00, (float) $invoice->amount_paid);
        $this->assertEquals(600.00, (float) $invoice->balance_due);
        $this->assertEquals('partially_paid', $invoice->status);

        // 2. Record Remaining Balance: 600 USD
        Payment::create([
            'tenant_id' => $tenant->id,
            'invoice_id' => $invoice->id,
            'customer_id' => $customer->id,
            'amount' => 600.00,
            'currency' => 'USD',
            'payment_date' => now(),
            'payment_method' => 'credit_card',
        ]);

        $invoice->refresh();
        $this->assertEquals(1000.00, (float) $invoice->amount_paid);
        $this->assertEquals(0.00, (float) $invoice->balance_due);
        $this->assertEquals('paid', $invoice->status);
        $this->assertNotNull($invoice->paid_at);
    }
}

