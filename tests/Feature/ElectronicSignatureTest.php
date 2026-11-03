<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\DocumentSignature;
use App\Models\Plan;
use App\Models\Proposal;
use App\Models\Tenant;
use App\Services\Documents\ElectronicSignatureService;
use App\Services\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectronicSignatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_electronic_signature_creates_tamper_evident_audit_log_and_locks_document(): void
    {
        $plan = Plan::create(['name' => 'Agency', 'slug' => 'agency', 'is_active' => true]);
        $tenant = Tenant::create(['plan_id' => $plan->id, 'name' => 'Agency Ltd', 'slug' => 'agency-ltd']);
        app(TenantContext::class)->setTenant($tenant);

        $customer = Customer::create([
            'tenant_id' => $tenant->id,
            'name' => 'Alice Cooper',
            'company_name' => 'Cooper Enterprises',
            'email' => 'alice@cooper.test',
        ]);

        $proposal = Proposal::create([
            'tenant_id' => $tenant->id,
            'customer_id' => $customer->id,
            'title' => 'Software Modernization Proposal',
            'status' => 'sent',
            'currency' => 'USD',
            'total_amount' => 5000.00,
            'issue_date' => now(),
        ]);

        $this->assertFalse($proposal->isSigned());

        $sigService = app(ElectronicSignatureService::class);
        $signature = $sigService->signDocument($proposal, [
            'signer_name' => 'Alice Cooper',
            'signer_email' => 'alice@cooper.test',
            'signer_title' => 'Chief Technology Officer',
            'signature_data' => 'Typed Signature: Alice Cooper',
            'signature_type' => 'typed',
            'consent_confirmed' => true,
            'ip_address' => '203.0.113.195',
            'user_agent' => 'Mozilla/5.0 Test Suite Agent',
        ]);

        $this->assertInstanceOf(DocumentSignature::class, $signature);
        $this->assertEquals('203.0.113.195', $signature->ip_address);
        $this->assertNotEmpty($signature->checksum_sha256);
        $this->assertEquals(64, strlen($signature->checksum_sha256)); // Valid SHA-256 length

        // Check document status is locked
        $proposal->refresh();
        $this->assertTrue($proposal->isSigned());
        $this->assertEquals('signed', $proposal->status);
        $this->assertNotNull($proposal->signed_at);
    }
}
