<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Plan;
use App\Models\Proposal;
use App\Models\Quotation;
use App\Models\Tenant;
use App\Services\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerPortalTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_view_and_accept_quotation_via_tokenized_portal(): void
    {
        $plan = Plan::create(['name' => 'Basic', 'slug' => 'basic', 'is_active' => true]);
        $tenant = Tenant::create(['plan_id' => $plan->id, 'name' => 'Agency Prime', 'slug' => 'agency-prime']);
        app(TenantContext::class)->setTenant($tenant);

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Thomas Anderson',
            'email' => 'neo@matrix.test',
        ]);

        $quote = Quotation::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'title' => 'System Infrastructure',
            'status' => 'sent',
            'issue_date' => now(),
            'currency' => 'USD',
            'subtotal' => 2500.00,
            'grand_total' => 2500.00,
        ]);

        // 1. Visit Portal
        $response = $this->get(route('portal.quote.view', ['token' => $quote->view_token]));
        $response->assertStatus(200);
        $response->assertSee('#' . $quote->quotation_number);
        $response->assertSee('System Infrastructure');

        // Quote should be marked as viewed
        $quote->refresh();
        $this->assertEquals('viewed', $quote->status);

        // 2. Submit Acceptance
        $acceptResponse = $this->post(route('portal.quote.accept', ['token' => $quote->view_token]), [
            'signer_name' => 'Thomas Anderson',
            'signer_email' => 'neo@matrix.test',
            'consent_confirmed' => '1',
            'signature_data' => 'Typed Signature: Thomas Anderson',
        ]);

        $acceptResponse->assertRedirect();
        $quote->refresh();
        $this->assertEquals('accepted', $quote->status);
        $this->assertNotNull($quote->accepted_at);
        $this->assertDatabaseHas('document_signatures', [
            'signer_email' => 'neo@matrix.test',
            'signable_id' => $quote->id,
        ]);
    }

    public function test_customer_can_view_and_sign_proposal_via_portal(): void
    {
        $plan = Plan::create(['name' => 'Basic', 'slug' => 'basic', 'is_active' => true]);
        $tenant = Tenant::create(['plan_id' => $plan->id, 'name' => 'DevOps Pros', 'slug' => 'devops-pros']);
        app(TenantContext::class)->setTenant($tenant);

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Bruce Wayne',
            'email' => 'bruce@wayne.test',
        ]);

        $proposal = Proposal::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'title' => 'Security Infrastructure Audit',
            'status' => 'sent',
            'currency' => 'USD',
            'total_amount' => 12000.00,
            'issue_date' => now(),
        ]);

        // Visit Signing Page
        $signPage = $this->get(route('portal.sign.show', ['signToken' => $proposal->sign_token]));
        $signPage->assertStatus(200);
        $signPage->assertSee('Electronic Signature Execution');

        // Execute Signature
        $executeResponse = $this->post(route('portal.sign.execute', ['signToken' => $proposal->sign_token]), [
            'signer_name' => 'Bruce Wayne',
            'signer_email' => 'bruce@wayne.test',
            'signer_title' => 'Chairman',
            'signature_data' => 'Typed Signature: Bruce Wayne',
            'consent_confirmed' => '1',
        ]);

        $executeResponse->assertRedirect();
        $proposal->refresh();
        $this->assertEquals('signed', $proposal->status);
        $this->assertTrue($proposal->isSigned());
    }
}

