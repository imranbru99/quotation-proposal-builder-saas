<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Customers (Companies & Individuals)
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('type', 20)->default('company'); // company, individual
            $table->string('name');
            $table->string('company_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('website')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country', 2)->default('US');
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('active'); // active, inactive, lead
            $table->boolean('portal_access_enabled')->default(false);
            $table->string('portal_token', 64)->nullable()->unique();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'email']);
        });

        // Product Categories
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
        });

        // Products & Services Catalog
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->string('sku')->nullable();
            $table->string('type', 20)->default('product'); // product, service
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2)->default(0);
            $table->decimal('cost', 12, 2)->default(0);
            $table->string('unit', 20)->default('item'); // item, hour, day, kg, month
            $table->decimal('tax_rate', 5, 2)->default(0); // e.g. 5.00 for 5%
            $table->string('currency', 3)->default('USD');
            $table->boolean('track_inventory')->default(false);
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->default(5);
            $table->string('image_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'is_active']);
            $table->unique(['tenant_id', 'sku']);
        });

        // Invoices (Declared before quotations so quotation foreign key can reference converted_invoice_id, or vice-versa)
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('source_quotation_id')->nullable();
            $table->unsignedBigInteger('source_proposal_id')->nullable();
            $table->string('invoice_number');
            $table->string('title')->nullable();
            $table->string('status', 20)->default('draft'); // draft, sent, partially_paid, paid, overdue, cancelled, refunded
            $table->date('issue_date');
            $table->date('due_date');
            $table->string('currency', 3)->default('USD');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->string('discount_type', 20)->default('percentage'); // percentage, fixed
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->decimal('balance_due', 12, 2)->default(0);
            $table->text('payment_terms')->nullable();
            $table->text('notes')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->uuid('view_token')->unique();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'invoice_number']);
            $table->index(['tenant_id', 'status']);
            $table->index(['tenant_id', 'due_date']);
        });

        // Invoice Line Items
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit', 20)->default('item');
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Quotations
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('converted_invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->string('quotation_number');
            $table->string('title')->nullable();
            $table->string('status', 25)->default('draft'); // draft, pending_approval, approved, sent, viewed, accepted, rejected, expired, cancelled, converted
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->string('currency', 3)->default('USD');
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->string('discount_type', 20)->default('percentage'); // percentage, fixed
            $table->decimal('discount_value', 10, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);
            $table->text('payment_terms')->nullable();
            $table->text('delivery_terms')->nullable();
            $table->text('notes')->nullable();
            $table->text('internal_notes')->nullable();
            $table->text('terms_and_conditions')->nullable();
            $table->uuid('view_token')->unique();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'quotation_number']);
            $table->index(['tenant_id', 'status']);
        });

        // Add foreign key constraint back to invoices for source_quotation_id
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreign('source_quotation_id')->references('id')->on('quotations')->nullOnDelete();
        });

        // Quotation Line Items
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quotation_id')->constrained('quotations')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2)->default(1);
            $table->string('unit', 20)->default('item');
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('discount_percentage', 5, 2)->default(0);
            $table->decimal('tax_percentage', 5, 2)->default(0);
            $table->decimal('tax_amount', 12, 2)->default(0);
            $table->decimal('total_price', 12, 2)->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Proposals
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('quotation_id')->nullable()->constrained('quotations')->nullOnDelete();
            $table->foreignId('converted_invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
            $table->string('proposal_number');
            $table->string('title');
            $table->string('status', 25)->default('draft'); // draft, sent, viewed, accepted, rejected, signed, converted
            $table->string('currency', 3)->default('USD');
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->date('issue_date');
            $table->date('valid_until')->nullable();
            $table->uuid('view_token')->unique();
            $table->uuid('sign_token')->unique();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('converted_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'proposal_number']);
            $table->index(['tenant_id', 'status']);
        });

        // Proposal Sections (Modular rich text sections)
        Schema::create('proposal_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->cascadeOnDelete();
            $table->string('section_type', 40)->default('custom'); // executive_summary, scope_of_work, deliverables, timeline, pricing, terms, portfolio, custom
            $table->string('title');
            $table->longText('content')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Proposal Payment Milestones
        Schema::create('proposal_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained('proposals')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->decimal('percentage', 5, 2)->default(0);
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('status', 20)->default('pending'); // pending, completed, invoiced
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Electronic Signatures (Immutable Audit Log)
        Schema::create('document_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->morphs('signable'); // App\Models\Quotation, App\Models\Proposal, App\Models\Invoice
            $table->string('signer_name');
            $table->string('signer_email');
            $table->string('signer_title')->nullable();
            $table->string('signature_type', 20)->default('drawn'); // drawn, typed
            $table->longText('signature_data'); // base64 SVG / canvas or font string
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->string('checksum_sha256', 64); // SHA-256 hash of document snapshot
            $table->boolean('consent_confirmed')->default(true);
            $table->timestamp('signed_at');
            $table->timestamps();

            $table->index(['tenant_id', 'signer_email']);
        });

        // Payments & Receipts
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('recorded_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('payment_number');
            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('USD');
            $table->date('payment_date');
            $table->string('payment_method', 30)->default('bank_transfer'); // bank_transfer, credit_card, cash, check, stripe, paypal, other
            $table->string('transaction_reference')->nullable();
            $table->text('notes')->nullable();
            $table->string('receipt_number')->nullable();
            $table->timestamps();

            $table->unique(['tenant_id', 'payment_number']);
            $table->index(['tenant_id', 'payment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('document_signatures');
        Schema::dropIfExists('proposal_milestones');
        Schema::dropIfExists('proposal_sections');
        Schema::dropIfExists('proposals');
        Schema::dropIfExists('quotation_items');
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['source_quotation_id']);
        });
        Schema::dropIfExists('quotations');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('customers');
    }
};
