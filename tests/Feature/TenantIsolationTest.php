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

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_cannot_see_another_tenants_data(): void
    {
        $plan = Plan::create([
            'name' => 'Standard',
            'slug' => 'standard',
            'price_monthly' => 10,
            'is_active' => true,
        ]);

        $tenantA = Tenant::create([
            'plan_id' => $plan->id,
            'name' => 'Company A',
            'slug' => 'company-a',
            'currency' => 'USD',
        ]);

        $tenantB = Tenant::create([
            'plan_id' => $plan->id,
            'name' => 'Company B',
            'slug' => 'company-b',
            'currency' => 'EUR',
        ]);

        $userA = User::create([
            'tenant_id' => $tenantA->id,
            'name' => 'User A',
            'email' => 'user.a@test.com',
            'password' => 'secret',
            'role' => 'owner',
        ]);

        $userB = User::create([
            'tenant_id' => $tenantB->id,
            'name' => 'User B',
            'email' => 'user.b@test.com',
            'password' => 'secret',
            'role' => 'owner',
        ]);

        // Create Customer for Tenant A
        app(TenantContext::class)->setTenant($tenantA);
        $customerA = Customer::create([
            'name' => 'Client A',
            'type' => 'company',
            'email' => 'client.a@test.com',
        ]);

        // Create Customer for Tenant B
        app(TenantContext::class)->setTenant($tenantB);
        $customerB = Customer::create([
            'name' => 'Client B',
            'type' => 'company',
            'email' => 'client.b@test.com',
        ]);

        // Context: Tenant A
        app(TenantContext::class)->setTenant($tenantA);
        $customersForA = Customer::all();
        $this->assertTrue($customersForA->contains($customerA));
        $this->assertFalse($customersForA->contains($customerB));
        $this->assertCount(1, $customersForA);

        // Context: Tenant B
        app(TenantContext::class)->setTenant($tenantB);
        $customersForB = Customer::all();
        $this->assertTrue($customersForB->contains($customerB));
        $this->assertFalse($customersForB->contains($customerA));
        $this->assertCount(1, $customersForB);
    }
}

