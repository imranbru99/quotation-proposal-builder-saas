# Module Development & Architecture Guide
## Quotation and Proposal Builder: Send, Sign, Convert to Invoice

This guide details the architectural rules and best practices for creating new business modules or extending existing capabilities within the platform.

---

## 1. Modular Architecture Principles

The platform is designed around a strictly decoupled, pluggable module system:

1. **Zero Data Loss Guarantee:** Deactivating a module must NEVER drop database tables, purge rows, or delete files. It strictly hides navigation, disables routes, and suspends scheduled jobs.
2. **Strict Multi-Tenancy:** Every module data model must enforce tenant isolation using the `BelongsToTenant` trait and `TenantScope`.
3. **Dependency Integrity:** Modules may declare prerequisites (e.g. `quotations` depends on `customers` and `products`). The system blocks deactivating a module if active dependents rely upon it.
4. **Conditional UI & Navigation:** Filament navigation items, dashboard widgets, and public catalog entries must be conditionally guarded using `is_module_active('module_id')`.

---

## 2. Anatomy of a Module Definition

Modules are registered in `App\Services\Modules\ModuleRegistry` using the `ModuleDefinition` value object:

```php
use App\Services\Modules\ModuleDefinition;

new ModuleDefinition(
    id: 'subscriptions',
    name: 'Recurring Subscriptions & Retainers',
    description: 'Automated recurring billing cycles, client retainers, and subscription renewals.',
    category: 'Finance & Billing',
    icon: 'heroicon-o-arrow-path',
    isCore: false,                 // true for mandatory core modules; false for optional packs
    dependencies: ['customers', 'invoices'],
    features: [
        'Automated Billing Cycles',
        'Prorated Invoicing',
        'Payment Gateway Auto-charge',
        'Dunning Reminders'
    ],
    config: [
        'auto_invoice' => true,
        'grace_period_days' => 3,
    ]
);
```

---

## 3. Step-by-Step: Creating a New Business Module

### Step 1: Database Migration
Always include `tenant_id` foreign key referencing the `tenants` table with cascading delete, and index appropriately:

```bash
php artisan make:migration create_subscriptions_tables
```

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->string('plan_name');
            $table->decimal('billing_amount', 12, 2);
            $table->string('billing_frequency')->default('monthly'); // monthly, yearly
            $table->string('status')->default('active');
            $table->date('next_billing_date');
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
```

---

### Step 2: Eloquent Model with Tenancy Trait
Always use `BelongsToTenant` and `$guarded = ['id']`:

```php
namespace App\Models;

use App\Traits\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant;

    protected $guarded = ['id'];

    protected $casts = [
        'billing_amount' => 'decimal:2',
        'next_billing_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
```

---

### Step 3: Register in ModuleRegistry
In `app/Services/Modules/ModuleRegistry.php`, register the new module under `registerOptionalModules()`:

```php
protected function registerOptionalModules(): void
{
    // ... existing modules
    $this->register(new ModuleDefinition(
        id: 'subscriptions',
        name: 'Recurring Subscriptions',
        description: 'Automated recurring billing cycles, retainers, and subscription management.',
        category: 'Finance & Billing',
        icon: 'heroicon-o-arrow-path',
        isCore: false,
        dependencies: ['customers', 'invoices'],
        features: ['Automated Billing Cycles', 'Retainer Management', 'Renewal Tracking']
    ));
}
```

---

### Step 4: Create Filament Resource with Module Guard
In your Filament Resource class, implement `canAccess()` or `shouldRegisterNavigation()` to dynamically honor the module's activation state:

```php
namespace App\Filament\Resources;

use App\Models\Subscription;
use Filament\Resources\Resource;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;
    protected static string | \UnitEnum | null $navigationGroup = 'Finance & Billing';
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-arrow-path';

    public static function shouldRegisterNavigation(): bool
    {
        return is_module_active('subscriptions');
    }

    public static function canAccess(): bool
    {
        return is_module_active('subscriptions');
    }
}
```

---

### Step 5: Web Routes & Middleware Protection
Protect any custom routes using the `is_module_active` check:

```php
Route::middleware(['auth', 'tenant'])->group(function () {
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])
        ->middleware(function ($request, $next) {
            abort_unless(is_module_active('subscriptions'), 404, 'Subscription module is not active.');
            return $next($request);
        });
});
```

---

## 4. Testing Your New Module

Every new module must include automated feature tests validating:
1. Module can be activated and deactivated via `ModuleManager`.
2. Historical records persist upon deactivation.
3. Access is blocked when inactive.
4. Tenant isolation is verified (Tenant A cannot see Tenant B's subscriptions).

Example test snippet:

```php
public function test_subscriptions_module_lifecycle(): void
{
    $manager = app(ModuleManager::class);
    $tenant = Tenant::factory()->create();

    // Activate
    $manager->activate('subscriptions', $tenant);
    $this->assertTrue($manager->isModuleActive('subscriptions', $tenant));

    // Create record
    $sub = Subscription::create([
        'tenant_id' => $tenant->id,
        'customer_id' => 1,
        'plan_name' => 'Monthly Retainer',
        'billing_amount' => 500.00,
        'next_billing_date' => now()->addMonth(),
    ]);

    // Deactivate
    $manager->deactivate('subscriptions', $tenant);
    $this->assertFalse($manager->isModuleActive('subscriptions', $tenant));

    // Assert zero data loss
    $this->assertDatabaseHas('subscriptions', ['id' => $sub->id]);
}
```

---

*Architected & Maintained by **Imran Ahmed** (`me@imrandev.bd`) • [https://imrandev.bd](https://imrandev.bd)*
