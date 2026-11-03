<?php

namespace Database\Seeders;

use App\Models\ActivityLog;
use App\Models\AutomationRule;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Proposal;
use App\Models\ProposalMilestone;
use App\Models\ProposalSection;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\Tenant;
use App\Models\TenantModule;
use App\Models\User;
use App\Services\Documents\ElectronicSignatureService;
use App\Services\Modules\ModuleRegistry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed SaaS Subscription Plans
        $freePlan = Plan::create([
            'name' => 'Free Starter',
            'slug' => 'free-starter',
            'description' => 'For independent freelancers testing quotes and basic invoicing.',
            'price_monthly' => 0.00,
            'price_annual' => 0.00,
            'max_users' => 1,
            'max_customers' => 15,
            'max_quotations_monthly' => 5,
            'max_invoices_monthly' => 5,
            'storage_limit_mb' => 200,
            'ai_token_limit_monthly' => 2500,
            'has_api_access' => false,
            'has_custom_branding' => false,
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 1,
        ]);

        $proPlan = Plan::create([
            'name' => 'Professional Growth',
            'slug' => 'professional-growth',
            'description' => 'For digital agencies, consultants, and growing service teams.',
            'price_monthly' => 29.00,
            'price_annual' => 290.00,
            'max_users' => 5,
            'max_customers' => 200,
            'max_quotations_monthly' => 100,
            'max_invoices_monthly' => 100,
            'storage_limit_mb' => 2000,
            'ai_token_limit_monthly' => 50000,
            'has_api_access' => true,
            'has_custom_branding' => true,
            'is_active' => true,
            'is_featured' => true,
            'sort_order' => 2,
        ]);

        $businessPlan = Plan::create([
            'name' => 'Business Enterprise',
            'slug' => 'business-enterprise',
            'description' => 'Complete operations: quotes, CRM, inventory, projects, and attendance.',
            'price_monthly' => 79.00,
            'price_annual' => 790.00,
            'max_users' => 25,
            'max_customers' => 2000,
            'max_quotations_monthly' => 1000,
            'max_invoices_monthly' => 1000,
            'storage_limit_mb' => 10000,
            'ai_token_limit_monthly' => 250000,
            'has_api_access' => true,
            'has_custom_branding' => true,
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 3,
        ]);

        $customPlan = Plan::create([
            'name' => 'Custom Scale',
            'slug' => 'custom-scale',
            'description' => 'Unlimited users, dedicated hosting support, and custom modules.',
            'price_monthly' => 199.00,
            'price_annual' => 1990.00,
            'max_users' => 100,
            'max_customers' => 10000,
            'max_quotations_monthly' => 5000,
            'max_invoices_monthly' => 5000,
            'storage_limit_mb' => 50000,
            'ai_token_limit_monthly' => 1000000,
            'has_api_access' => true,
            'has_custom_branding' => true,
            'is_active' => true,
            'is_featured' => false,
            'sort_order' => 4,
        ]);

        // 2. Seed Platform SuperAdmin
        $superAdmin = User::create([
            'name' => 'Imran Ahmed (SuperAdmin)',
            'email' => 'admin@platform.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_platform_superadmin' => true,
            'is_active' => true,
            'job_title' => 'Chief Platform Architect',
            'email_verified_at' => now(),
        ]);

        // 3. Seed Demo Tenant: "Demo Business Solutions"
        $demoTenant = Tenant::create([
            'plan_id' => $proPlan->id,
            'name' => 'Demo Business Solutions',
            'slug' => 'demo-business-solutions',
            'company_email' => 'contact@demobusiness.test',
            'company_phone' => '+1 (555) 019-2834',
            'website' => 'https://demobusiness.test',
            'tax_id' => 'TAX-US-998822',
            'vat_id' => 'VAT-US-102938',
            'address' => '100 Innovation Way, Suite 400',
            'city' => 'Austin',
            'state' => 'TX',
            'postal_code' => '78701',
            'country' => 'US',
            'currency' => 'USD',
            'currency_symbol' => '$',
            'plan_status' => 'active',
            'trial_ends_at' => now()->addDays(30),
            'subscription_ends_at' => now()->addYear(),
            'is_active' => true,
            'is_demo' => true,
        ]);

        // 4. Activate Modules for Demo Tenant
        $registry = app(ModuleRegistry::class);
        foreach ($registry->all() as $id => $module) {
            TenantModule::create([
                'tenant_id' => $demoTenant->id,
                'module_id' => $id,
                'is_active' => true,
                'activated_at' => now(),
            ]);
        }

        // 5. Seed Demo Users
        $owner = User::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Alex Vance (Owner)',
            'email' => 'owner@demo.test',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'job_title' => 'Managing Director',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $sales = User::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Sarah Connor (Sales)',
            'email' => 'sales@demo.test',
            'password' => Hash::make('password'),
            'role' => 'sales_executive',
            'job_title' => 'Senior Account Executive',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $accountant = User::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'David Miller (Finance)',
            'email' => 'accountant@demo.test',
            'password' => Hash::make('password'),
            'role' => 'accountant',
            'job_title' => 'Head of Accounting',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // 6. Seed Product Categories & Products
        $devCategory = ProductCategory::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Engineering & Development',
            'slug' => 'engineering-development',
            'description' => 'Custom software, cloud architecture, and web platforms.',
        ]);

        $designCategory = ProductCategory::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Design & Branding',
            'slug' => 'design-branding',
            'description' => 'UI/UX interface design, branding, and design systems.',
        ]);

        $prodWeb = Product::create([
            'tenant_id' => $demoTenant->id,
            'category_id' => $devCategory->id,
            'name' => 'Full-Stack SaaS Platform Engineering',
            'slug' => 'full-stack-saas-platform-engineering',
            'sku' => 'SRV-DEV-001',
            'type' => 'service',
            'description' => 'Complete Laravel & Vue/Livewire modular application development.',
            'price' => 4500.00,
            'cost' => 2000.00,
            'unit' => 'item',
            'tax_rate' => 5.00,
            'currency' => 'USD',
            'is_active' => true,
        ]);

        $prodUi = Product::create([
            'tenant_id' => $demoTenant->id,
            'category_id' => $designCategory->id,
            'name' => 'UI/UX Design System & Prototypes',
            'slug' => 'ui-ux-design-system-prototypes',
            'sku' => 'SRV-DSN-002',
            'type' => 'service',
            'description' => 'High-fidelity Figma prototypes, design tokens, and Tailwind theme.',
            'price' => 1800.00,
            'cost' => 800.00,
            'unit' => 'item',
            'tax_rate' => 5.00,
            'currency' => 'USD',
            'is_active' => true,
        ]);

        $prodConsult = Product::create([
            'tenant_id' => $demoTenant->id,
            'category_id' => $devCategory->id,
            'name' => 'Cloud Infrastructure & DevOps Setup',
            'slug' => 'cloud-infrastructure-devops-setup',
            'sku' => 'SRV-OPS-003',
            'type' => 'service',
            'description' => 'Docker, CI/CD pipeline, automated SSL, and database clustering.',
            'price' => 1200.00,
            'cost' => 400.00,
            'unit' => 'item',
            'tax_rate' => 5.00,
            'currency' => 'USD',
            'is_active' => true,
        ]);

        // 7. Seed Sample Customers
        $customerAcme = Customer::create([
            'tenant_id' => $demoTenant->id,
            'type' => 'company',
            'name' => 'Johnathan Sterling',
            'company_name' => 'Acme Corporation International',
            'email' => 'client@acmecorp.test',
            'phone' => '+1 (555) 789-0123',
            'tax_number' => 'US-EIN-449911',
            'vat_number' => 'VAT-GB-992211',
            'website' => 'https://acmecorp.test',
            'currency' => 'USD',
            'billing_address' => '742 Evergreen Terrace',
            'city' => 'Springfield',
            'state' => 'IL',
            'postal_code' => '62704',
            'country' => 'US',
            'status' => 'active',
            'portal_access_enabled' => true,
        ]);

        $customerZenith = Customer::create([
            'tenant_id' => $demoTenant->id,
            'type' => 'company',
            'name' => 'Elena Rostova',
            'company_name' => 'Zenith Global Logistics',
            'email' => 'elena@zenithlogistics.test',
            'phone' => '+44 20 7946 0912',
            'currency' => 'USD',
            'billing_address' => '12 Bishopgate Street',
            'city' => 'London',
            'postal_code' => 'EC2N 4BQ',
            'country' => 'GB',
            'status' => 'active',
            'portal_access_enabled' => true,
        ]);

        // 8. Seed Sample Quotation 1: Accepted Quotation
        $quote1 = Quotation::create([
            'tenant_id' => $demoTenant->id,
            'customer_id' => $customerAcme->id,
            'creator_id' => $sales->id,
            'title' => 'Enterprise E-Commerce Portal Modernization',
            'status' => 'accepted',
            'issue_date' => now()->subDays(5),
            'expiry_date' => now()->addDays(25),
            'currency' => 'USD',
            'subtotal' => 6300.00,
            'discount_type' => 'percentage',
            'discount_value' => 5.00,
            'discount_amount' => 315.00,
            'tax_amount' => 299.25,
            'grand_total' => 6284.25,
            'payment_terms' => '30% deposit upon acceptance, 70% upon completion.',
            'delivery_terms' => '4-week phased sprint delivery.',
            'notes' => 'Includes 60 days of post-launch priority bugfixing and updates.',
            'terms_and_conditions' => 'Standard Master Services Agreement applies.',
            'sent_at' => now()->subDays(5),
            'viewed_at' => now()->subDays(4),
            'accepted_at' => now()->subDays(3),
        ]);

        QuotationItem::create([
            'quotation_id' => $quote1->id,
            'product_id' => $prodWeb->id,
            'item_name' => $prodWeb->name,
            'description' => 'Custom Laravel 12 multi-tenant core backend & API development.',
            'quantity' => 1,
            'unit' => 'item',
            'unit_price' => 4500.00,
            'tax_percentage' => 5.00,
            'tax_amount' => 225.00,
            'total_price' => 4725.00,
            'sort_order' => 1,
        ]);

        QuotationItem::create([
            'quotation_id' => $quote1->id,
            'product_id' => $prodUi->id,
            'item_name' => $prodUi->name,
            'description' => 'Interactive UI components, Tailwind CSS styling, responsive views.',
            'quantity' => 1,
            'unit' => 'item',
            'unit_price' => 1800.00,
            'tax_percentage' => 5.00,
            'tax_amount' => 90.00,
            'total_price' => 1890.00,
            'sort_order' => 2,
        ]);

        // 9. Seed Sample Quotation 2: Sent Quotation (Pending Review)
        $quote2 = Quotation::create([
            'tenant_id' => $demoTenant->id,
            'customer_id' => $customerZenith->id,
            'creator_id' => $sales->id,
            'title' => 'Cloud Infrastructure & High Availability Setup',
            'status' => 'sent',
            'issue_date' => now()->subDays(1),
            'expiry_date' => now()->addDays(14),
            'currency' => 'USD',
            'subtotal' => 1200.00,
            'discount_type' => 'fixed',
            'discount_value' => 0.00,
            'discount_amount' => 0.00,
            'tax_amount' => 60.00,
            'grand_total' => 1260.00,
            'payment_terms' => 'Net 14 Days.',
            'notes' => 'Prepared after our technical discovery meeting.',
            'sent_at' => now()->subDays(1),
        ]);

        QuotationItem::create([
            'quotation_id' => $quote2->id,
            'product_id' => $prodConsult->id,
            'item_name' => $prodConsult->name,
            'description' => 'Automated deployment setup, SSL certificates, daily backup routines.',
            'quantity' => 1,
            'unit' => 'item',
            'unit_price' => 1200.00,
            'tax_percentage' => 5.00,
            'tax_amount' => 60.00,
            'total_price' => 1260.00,
            'sort_order' => 1,
        ]);

        // 10. Seed Sample Proposal: Electronically Signed Proposal
        $proposal = Proposal::create([
            'tenant_id' => $demoTenant->id,
            'customer_id' => $customerAcme->id,
            'creator_id' => $owner->id,
            'quotation_id' => $quote1->id,
            'title' => 'Strategic Digital Transformation & Architecture Agreement',
            'status' => 'signed',
            'currency' => 'USD',
            'total_amount' => 7500.00,
            'issue_date' => now()->subDays(7),
            'valid_until' => now()->addDays(23),
            'sent_at' => now()->subDays(7),
            'viewed_at' => now()->subDays(6),
            'accepted_at' => now()->subDays(5),
            'signed_at' => now()->subDays(5),
        ]);

        ProposalSection::create([
            'proposal_id' => $proposal->id,
            'section_type' => 'executive_summary',
            'title' => 'Executive Summary',
            'content' => "Acme Corporation International is expanding its digital capabilities. Demo Business Solutions is proud to provide end-to-end engineering, high-availability architecture, and modular SaaS tools that empower Acme to accelerate operational output while guaranteeing 100% data integrity.",
            'sort_order' => 1,
        ]);

        ProposalSection::create([
            'proposal_id' => $proposal->id,
            'section_type' => 'scope_of_work',
            'title' => 'Scope of Work & Deliverables',
            'content' => "1. Domain Modeling & Database Schema Design\n2. Multi-Tenant Role-Based Access Control Setup\n3. Responsive Customer Facing Web Portals\n4. E-Signature Legal Audit Trails\n5. Automated Invoicing and PDF Engine Integration",
            'sort_order' => 2,
        ]);

        ProposalMilestone::create([
            'proposal_id' => $proposal->id,
            'title' => 'Milestone 1: Architecture Blueprint & Approval',
            'due_date' => now()->addDays(7),
            'percentage' => 40.00,
            'amount' => 3000.00,
            'status' => 'completed',
            'sort_order' => 1,
        ]);

        ProposalMilestone::create([
            'proposal_id' => $proposal->id,
            'title' => 'Milestone 2: Final Delivery, Testing & Handoff',
            'due_date' => now()->addDays(28),
            'percentage' => 60.00,
            'amount' => 4500.00,
            'status' => 'pending',
            'sort_order' => 2,
        ]);

        // Attach Electronic Signature to Proposal
        $sigService = app(ElectronicSignatureService::class);
        $sigService->signDocument($proposal, [
            'signer_name' => 'Johnathan Sterling',
            'signer_email' => 'client@acmecorp.test',
            'signer_title' => 'Vice President of Technology',
            'signature_data' => 'Typed Signature: Johnathan Sterling',
            'signature_type' => 'typed',
            'consent_confirmed' => true,
            'ip_address' => '198.51.100.42',
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.0.0 Safari/537.36',
        ]);

        // 11. Seed Converted Invoice & Partial Payment
        $invoice = Invoice::create([
            'tenant_id' => $demoTenant->id,
            'customer_id' => $customerAcme->id,
            'creator_id' => $accountant->id,
            'source_quotation_id' => $quote1->id,
            'title' => 'Invoice for Quote #' . $quote1->quotation_number,
            'status' => 'partially_paid',
            'issue_date' => now()->subDays(2),
            'due_date' => now()->addDays(12),
            'currency' => 'USD',
            'subtotal' => 6300.00,
            'discount_type' => 'percentage',
            'discount_value' => 5.00,
            'discount_amount' => 315.00,
            'tax_amount' => 299.25,
            'grand_total' => 6284.25,
            'amount_paid' => 3000.00,
            'balance_due' => 3284.25,
            'payment_terms' => 'Net 14 Days.',
            'notes' => 'Thank you for your business!',
            'sent_at' => now()->subDays(2),
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'product_id' => $prodWeb->id,
            'item_name' => $prodWeb->name,
            'description' => 'Custom Laravel 12 multi-tenant core backend & API development.',
            'quantity' => 1,
            'unit' => 'item',
            'unit_price' => 4500.00,
            'tax_percentage' => 5.00,
            'tax_amount' => 225.00,
            'total_price' => 4725.00,
            'sort_order' => 1,
        ]);

        InvoiceItem::create([
            'invoice_id' => $invoice->id,
            'product_id' => $prodUi->id,
            'item_name' => $prodUi->name,
            'description' => 'Interactive UI components, Tailwind CSS styling, responsive views.',
            'quantity' => 1,
            'unit' => 'item',
            'unit_price' => 1800.00,
            'tax_percentage' => 5.00,
            'tax_amount' => 90.00,
            'total_price' => 1890.00,
            'sort_order' => 2,
        ]);

        // Mark Quotation 1 as converted to this invoice
        $quote1->update([
            'converted_invoice_id' => $invoice->id,
            'status' => 'converted',
            'converted_at' => now()->subDays(2),
        ]);

        // Seed 1st Installment Payment
        Payment::create([
            'tenant_id' => $demoTenant->id,
            'invoice_id' => $invoice->id,
            'customer_id' => $customerAcme->id,
            'recorded_by_id' => $accountant->id,
            'amount' => 3000.00,
            'currency' => 'USD',
            'payment_date' => now()->subDays(1),
            'payment_method' => 'bank_transfer',
            'transaction_reference' => 'WIRE-REF-99228811',
            'notes' => 'Initial 30% upfront deposit received via Wire Transfer.',
        ]);

        // 12. Seed Sample Automation Rule
        AutomationRule::create([
            'tenant_id' => $demoTenant->id,
            'name' => 'Auto-Convert Accepted Quotes to Invoices',
            'event_trigger' => 'quotation.accepted',
            'action_type' => 'convert_to_invoice',
            'is_active' => true,
        ]);

        // 13. Seed Initial Activity Logs
        ActivityLog::create([
            'tenant_id' => $demoTenant->id,
            'user_id' => $owner->id,
            'action' => 'created',
            'subject_type' => Tenant::class,
            'subject_id' => $demoTenant->id,
            'description' => 'Demo company workspace initialized with full enterprise module suite.',
            'ip_address' => '127.0.0.1',
        ]);
    }
}
