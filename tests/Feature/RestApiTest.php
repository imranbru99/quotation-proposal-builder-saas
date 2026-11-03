<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Plan;
use App\Models\Quotation;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestApiTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected User $user;
    protected string $token;
    protected Customer $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $plan = Plan::create([
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'price_monthly' => 99,
            'is_active' => true,
        ]);

        $this->tenant = Tenant::create([
            'plan_id' => $plan->id,
            'name' => 'API Corp',
            'slug' => 'api-corp',
            'currency' => 'USD',
        ]);

        $this->user = User::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'API Developer',
            'email' => 'api@dev.test',
            'password' => bcrypt('secret123'),
            'role' => 'owner',
            'is_active' => true,
        ]);

        app(TenantContext::class)->setTenant($this->tenant);

        $this->customer = Customer::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Client Zeta',
            'type' => 'company',
            'email' => 'zeta@client.test',
        ]);

        $this->token = $this->user->createToken('test-token')->plainTextToken;
    }

    public function test_api_login_returns_token(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'api@dev.test',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'token',
            'user' => ['id', 'name', 'email', 'role', 'tenant_id'],
        ]);
    }

    public function test_api_me_endpoint_returns_user_and_tenant(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->getJson('/api/v1/auth/me');

        $response->assertStatus(200);
        $response->assertJsonPath('email', 'api@dev.test');
        $response->assertJsonPath('tenant.id', $this->tenant->id);
    }

    public function test_api_creates_and_lists_customers(): void
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->postJson('/api/v1/customers', [
            'name' => 'New API Customer',
            'type' => 'company',
            'email' => 'api.customer@test.com',
            'company_name' => 'API Customer Inc',
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('customer.name', 'New API Customer');

        $list = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->getJson('/api/v1/customers');

        $list->assertStatus(200);
        $list->assertJsonFragment(['name' => 'New API Customer']);
    }

    public function test_api_creates_quotation_and_converts_to_invoice(): void
    {
        // 1. Create Quotation via API
        $createRes = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->postJson('/api/v1/quotations', [
            'customer_id' => $this->customer->id,
            'title' => 'API Cloud Migration',
            'issue_date' => now()->toDateString(),
            'expiry_date' => now()->addDays(30)->toDateString(),
            'items' => [
                [
                    'item_name' => 'Cloud Migration Service',
                    'quantity' => 2,
                    'unit_price' => 500,
                    'tax_percentage' => 10,
                ],
            ],
        ]);

        $createRes->assertStatus(201);
        $quoteId = $createRes->json('quotation.id');
        $this->assertNotNull($quoteId);
        $this->assertEquals(1100.00, (float) $createRes->json('quotation.total_amount'));

        // Mark quote as accepted
        Quotation::where('id', $quoteId)->update(['status' => 'accepted']);

        // 2. Convert Quotation to Invoice via API
        $convertRes = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->postJson("/api/v1/quotations/{$quoteId}/convert-to-invoice", [
            'due_date' => now()->addDays(14)->toDateString(),
            'notes' => 'Converted via API test',
        ]);

        $convertRes->assertStatus(200);
        $convertRes->assertJsonStructure([
            'message',
            'invoice' => ['id', 'invoice_number', 'total_amount', 'status'],
        ]);
        $this->assertEquals('sent', $convertRes->json('invoice.status'));
        $this->assertEquals(1100.00, (float) $convertRes->json('invoice.total_amount'));

        // 3. Repeated conversion is idempotent: returns existing invoice with 200
        $repeatRes = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->postJson("/api/v1/quotations/{$quoteId}/convert-to-invoice");

        $repeatRes->assertStatus(200);
        $this->assertEquals($convertRes->json('invoice.id'), $repeatRes->json('invoice.id'));

        // 4. Attempting to convert a rejected quotation fails with 422
        $rejectedQuote = Quotation::create([
            'tenant_id' => $this->tenant->id,
            'customer_id' => $this->customer->id,
            'creator_id' => $this->user->id,
            'title' => 'Rejected Quote',
            'status' => 'rejected',
            'issue_date' => now()->toDateString(),
        ]);

        $invalidConvertRes = $this->withHeaders([
            'Authorization' => "Bearer {$this->token}",
        ])->postJson("/api/v1/quotations/{$rejectedQuote->id}/convert-to-invoice");

        $invalidConvertRes->assertStatus(422);
    }
}
