<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\Warehouse;
use App\Services\Modules\ModuleManager;
use App\Services\Tenancy\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleActivationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_activate_and_deactivate_module_without_deleting_historical_data(): void
    {
        $plan = Plan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'price_monthly' => 20,
            'is_active' => true,
        ]);

        $tenant = Tenant::create([
            'plan_id' => $plan->id,
            'name' => 'Acme Corp',
            'slug' => 'acme-corp',
        ]);

        app(TenantContext::class)->setTenant($tenant);
        $manager = app(ModuleManager::class);

        // 1. Activate inventory module
        $res = $manager->activate('inventory', $tenant);
        $this->assertTrue($res['success']);
        $this->assertTrue($manager->isModuleActive('inventory', $tenant));

        // 2. Insert a warehouse record under active module
        $warehouse = Warehouse::create([
            'name' => 'Main Distribution Center',
            'location' => 'Austin, TX',
            'is_primary' => true,
        ]);
        $this->assertDatabaseHas('warehouses', ['id' => $warehouse->id]);

        // 3. Deactivate inventory module
        $deactRes = $manager->deactivate('inventory', $tenant);
        $this->assertTrue($deactRes['success']);
        $this->assertFalse($manager->isModuleActive('inventory', $tenant));

        // 4. CRITICAL CHECK: Verify database table and row was NEVER deleted
        $this->assertDatabaseHas('warehouses', [
            'id' => $warehouse->id,
            'name' => 'Main Distribution Center',
        ]);
    }

    public function test_cannot_deactivate_module_if_active_dependents_exist(): void
    {
        $plan = Plan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'is_active' => true,
        ]);

        $tenant = Tenant::create([
            'plan_id' => $plan->id,
            'name' => 'Beta Logistics',
            'slug' => 'beta-logistics',
        ]);

        $manager = app(ModuleManager::class);

        // Activate customers and quotations (quotations depends on customers)
        $manager->activate('customers', $tenant);
        $manager->activate('products', $tenant);
        $manager->activate('quotations', $tenant);

        // Attempting to deactivate 'customers' while 'quotations' is active must be blocked
        $result = $manager->deactivate('customers', $tenant);
        $this->assertFalse($result['success']);
        $this->assertStringContainsString('active modules depend on it', $result['message']);
    }
}
