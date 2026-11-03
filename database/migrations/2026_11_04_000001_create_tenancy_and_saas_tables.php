<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // SaaS Subscription Plans
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 10, 2)->default(0);
            $table->decimal('price_annual', 10, 2)->default(0);
            $table->integer('max_users')->default(1);
            $table->integer('max_customers')->default(50);
            $table->integer('max_quotations_monthly')->default(20);
            $table->integer('max_invoices_monthly')->default(20);
            $table->integer('storage_limit_mb')->default(500);
            $table->integer('ai_token_limit_monthly')->default(10000);
            $table->json('included_modules')->nullable(); // list of module IDs enabled in this plan
            $table->boolean('has_api_access')->default(false);
            $table->boolean('has_custom_branding')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Tenants (Businesses / Workspaces)
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->nullable()->constrained('plans')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('company_email')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('website')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('vat_id')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country', 2)->default('US');
            $table->string('currency', 3)->default('USD');
            $table->string('currency_symbol', 10)->default('$');
            $table->string('logo_path')->nullable();
            $table->string('signature_path')->nullable();
            $table->string('primary_color', 20)->default('#2563eb');
            $table->string('plan_status', 20)->default('active'); // active, trialing, past_due, cancelled
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->json('settings')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_demo')->default(false);
            $table->timestamps();
        });

        // Tenant Modules (Feature activation per tenant)
        Schema::create('tenant_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('module_id');
            $table->boolean('is_active')->default(true);
            $table->json('settings')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'module_id']);
        });

        // Update Users table with tenancy & RBAC
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->cascadeOnDelete();
            $table->string('role', 30)->default('owner')->after('email'); // owner, admin, sales_manager, sales_executive, accountant, employee, customer
            $table->string('phone')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('phone');
            $table->string('job_title')->nullable()->after('avatar');
            $table->boolean('is_platform_superadmin')->default(false)->after('job_title');
            $table->boolean('is_active')->default(true)->after('is_platform_superadmin');
            $table->json('custom_permissions')->nullable()->after('is_active');
            $table->string('locale', 10)->default('en')->after('custom_permissions');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn([
                'tenant_id',
                'role',
                'phone',
                'avatar',
                'job_title',
                'is_platform_superadmin',
                'is_active',
                'custom_permissions',
                'locale',
            ]);
        });

        Schema::dropIfExists('tenant_modules');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('plans');
    }
};

