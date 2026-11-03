<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Plan::create([
            'name' => 'Starter',
            'slug' => 'starter',
            'price_monthly' => 19,
            'is_active' => true,
            'sort_order' => 1,
            'features' => ['Quotations', 'Invoices', 'Electronic Signatures'],
        ]);

        Plan::create([
            'name' => 'Professional',
            'slug' => 'professional',
            'price_monthly' => 49,
            'is_active' => true,
            'sort_order' => 2,
            'features' => ['All Starter Features', 'CRM', 'Projects', 'Attendance'],
        ]);
    }

    public function test_homepage_loads_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Quotation and Proposal Builder');
        $response->assertSee('Starter');
        $response->assertSee('Professional');
    }

    public function test_modules_catalog_page_loads(): void
    {
        $response = $this->get('/modules');

        $response->assertStatus(200);
        $response->assertSee('Quotation Builder');
        $response->assertSee('Invoice Management');
    }

    public function test_single_module_page_loads(): void
    {
        $response = $this->get('/modules/quotations');

        $response->assertStatus(200);
        $response->assertSee('Quotation Builder');
    }

    public function test_pricing_page_loads(): void
    {
        $response = $this->get('/pricing');

        $response->assertStatus(200);
        $response->assertSee('Starter');
        $response->assertSee('$19');
    }

    public function test_solutions_page_loads(): void
    {
        $response = $this->get('/solutions');

        $response->assertStatus(200);
        $response->assertSee('Freelancers');
    }

    public function test_ai_features_page_loads(): void
    {
        $response = $this->get('/ai-features');

        $response->assertStatus(200);
        $response->assertSee('AI');
    }

    public function test_security_page_loads(): void
    {
        $response = $this->get('/security');

        $response->assertStatus(200);
        $response->assertSee('Security');
    }

    public function test_documentation_page_loads(): void
    {
        $response = $this->get('/docs');

        $response->assertStatus(200);
        $response->assertSee('Documentation');
    }

    public function test_demo_login_switches_user_and_redirects(): void
    {
        $plan = Plan::first();
        $tenant = Tenant::create([
            'plan_id' => $plan->id,
            'name' => 'Demo Tenant',
            'slug' => 'demo-tenant',
            'is_demo' => true,
        ]);

        $owner = User::create([
            'tenant_id' => $tenant->id,
            'name' => 'Demo Owner',
            'email' => 'owner@demo.test',
            'password' => 'secret',
            'role' => 'owner',
        ]);

        $response = $this->get('/demo/login/owner');

        $response->assertRedirect('/app');
        $this->assertAuthenticatedAs($owner);
    }
}
