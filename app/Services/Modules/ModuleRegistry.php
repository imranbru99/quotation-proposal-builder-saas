<?php

namespace App\Services\Modules;

use Illuminate\Support\Collection;

class ModuleRegistry
{
    /** @var array<string, ModuleDefinition> */
    protected array $modules = [];

    public function __construct()
    {
        $this->registerCoreModules();
        $this->registerOptionalModules();
    }

    public function register(ModuleDefinition $module): self
    {
        $this->modules[$module->id] = $module;
        return $this;
    }

    public function all(): Collection
    {
        return collect($this->modules);
    }

    public function find(string $id): ?ModuleDefinition
    {
        return $this->modules[$id] ?? null;
    }

    public function exists(string $id): bool
    {
        return isset($this->modules[$id]);
    }

    public function byCategory(): Collection
    {
        return $this->all()->groupBy('category');
    }

    /**
     * Determine if a module can be safely deactivated without breaking active dependents.
     *
     * @param string $moduleId
     * @param array<string> $activeModuleIds
     * @return array{can_deactivate: bool, dependents: array<string>}
     */
    public function canDeactivate(string $moduleId, array $activeModuleIds): array
    {
        $dependents = [];

        foreach ($activeModuleIds as $activeId) {
            if ($activeId === $moduleId) {
                continue;
            }

            $mod = $this->find($activeId);
            if ($mod && in_array($moduleId, $mod->dependencies, true)) {
                $dependents[] = $mod->name;
            }
        }

        return [
            'can_deactivate' => empty($dependents),
            'dependents' => $dependents,
        ];
    }

    /**
     * Check if all prerequisites of a module are satisfied.
     */
    public function getMissingDependencies(string $moduleId, array $activeModuleIds): array
    {
        $mod = $this->find($moduleId);
        if (!$mod) {
            return [];
        }

        $missing = [];
        foreach ($mod->dependencies as $depId) {
            if (!in_array($depId, $activeModuleIds, true)) {
                $missing[] = $this->find($depId)?->name ?? $depId;
            }
        }

        return $missing;
    }

    protected function registerCoreModules(): void
    {
        $this->register(new ModuleDefinition(
            id: 'customers',
            name: 'Customer & Client Directory',
            description: 'Centralized directory for companies, individuals, contact details, tax numbers, and communication history.',
            category: 'Core Business',
            icon: 'heroicon-o-user-group',
            isCore: true,
            features: ['Company & Individual Clients', 'Tax / VAT Numbers', 'Multiple Addresses', 'Client Portal Invitations']
        ));

        $this->register(new ModuleDefinition(
            id: 'products',
            name: 'Products & Services Catalog',
            description: 'Item catalogs with SKUs, units, pricing, cost tracking, tax rates, and AI enrichment.',
            category: 'Core Business',
            icon: 'heroicon-o-shopping-bag',
            isCore: true,
            features: ['Goods & Services Pricing', 'SKU Management', 'Multi-currency Support', 'Tax Rule Association']
        ));

        $this->register(new ModuleDefinition(
            id: 'quotations',
            name: 'Quotation Builder',
            description: 'Interactive estimate and quotation lifecycle from draft to approval, client acceptance, and 1-click invoice conversion.',
            category: 'Sales',
            icon: 'heroicon-o-document-text',
            isCore: true,
            dependencies: ['customers', 'products'],
            features: ['Custom Line Items & Discounts', 'PDF Export & Public Review Links', 'Accept / Reject Workflows', '1-Click Invoice Conversion']
        ));

        $this->register(new ModuleDefinition(
            id: 'proposals',
            name: 'Proposal Builder',
            description: 'Rich modular proposals with executive summaries, scope of work, deliverables, timelines, and payment milestones.',
            category: 'Sales',
            icon: 'heroicon-o-newspaper',
            isCore: true,
            dependencies: ['customers'],
            features: ['Modular Sections', 'Payment Milestones', 'Portfolio & Branding', 'Client Review Links']
        ));

        $this->register(new ModuleDefinition(
            id: 'esignature',
            name: 'Electronic Signatures',
            description: 'Tamper-evident legal electronic signature collection with consent recording, IP/UA capture, and SHA-256 document locking.',
            category: 'Legal & Sales',
            icon: 'heroicon-o-pencil-square',
            isCore: true,
            dependencies: ['customers'],
            features: ['Drawn & Typed Signatures', 'SHA-256 Document Lock', 'Immutable Audit Trail', 'Jurisdiction Notice']
        ));

        $this->register(new ModuleDefinition(
            id: 'invoices',
            name: 'Invoice Management',
            description: 'Billing engine with line item snapshots, tax computation, balance tracking, overdue status, and PDF generator.',
            category: 'Finance',
            icon: 'heroicon-o-banknotes',
            isCore: true,
            dependencies: ['customers', 'products'],
            features: ['Quotation-to-Invoice Conversion', 'Partial Payments', 'Due Date Tracking', 'Unicode PDF Generation']
        ));

        $this->register(new ModuleDefinition(
            id: 'payments',
            name: 'Payments & Receipts',
            description: 'Payment reconciliation, transaction reference tracking, receipt generation, and balance updates.',
            category: 'Finance',
            icon: 'heroicon-o-credit-card',
            isCore: true,
            dependencies: ['invoices'],
            features: ['Multi-method Logging', 'Payment Receipts', 'Balance Due Reconciliation']
        ));
    }

    protected function registerOptionalModules(): void
    {
        $this->register(new ModuleDefinition(
            id: 'crm',
            name: 'CRM & Pipeline',
            description: 'Lead capture, sales pipeline stages, opportunity valuation, and deal conversion tracking.',
            category: 'Sales',
            icon: 'heroicon-o-funnel',
            isCore: false,
            dependencies: ['customers'],
            features: ['Lead Scoring & Sources', 'Deal Stages (Discovery to Won)', 'Follow-up Activities']
        ));

        $this->register(new ModuleDefinition(
            id: 'projects',
            name: 'Project & Task Management',
            description: 'Project delivery tracking, client milestones, tasks, team assignment, and budget monitoring.',
            category: 'Delivery',
            icon: 'heroicon-o-clipboard-document-check',
            isCore: false,
            dependencies: ['customers'],
            features: ['Milestone Tracking', 'Task Priorities', 'Budget vs Actual Expenses', 'Team Assignments']
        ));

        $this->register(new ModuleDefinition(
            id: 'expenses',
            name: 'Expense Management',
            description: 'Track operational and project expenses, store receipt attachments, and categorize tax write-offs.',
            category: 'Finance',
            icon: 'heroicon-o-receipt-percent',
            isCore: false,
            dependencies: [],
            features: ['Receipt Attachments', 'Project Association', 'Category Breakdown', 'Approval Workflow']
        ));

        $this->register(new ModuleDefinition(
            id: 'inventory',
            name: 'Inventory & Warehousing',
            description: 'Multi-warehouse stock tracking, low stock alerts, stock adjustments, and movements.',
            category: 'Operations',
            icon: 'heroicon-o-building-storefront',
            isCore: false,
            dependencies: ['products'],
            features: ['Multiple Warehouses', 'Stock Level Tracking', 'Low Stock Threshold Alerts']
        ));

        $this->register(new ModuleDefinition(
            id: 'purchases',
            name: 'Purchase Orders & Suppliers',
            description: 'Supplier directory, purchase orders, goods receiving, and procurement expense tracking.',
            category: 'Operations',
            icon: 'heroicon-o-truck',
            isCore: false,
            dependencies: ['products'],
            features: ['Supplier Directory', 'Purchase Orders', 'Cost Tracking']
        ));

        $this->register(new ModuleDefinition(
            id: 'employees',
            name: 'Staff & HR Directory',
            description: 'Staff profiles, organizational departments, job designations, salary records, and document vaults.',
            category: 'HR',
            icon: 'heroicon-o-briefcase',
            isCore: false,
            dependencies: [],
            features: ['Department Organization', 'Staff Profiles', 'Leave Requests', 'Salary Records']
        ));

        $this->register(new ModuleDefinition(
            id: 'attendance',
            name: 'Biometric Attendance Hub',
            description: 'Sync employee attendance via biometric hardware terminals or browser simulation powered by laravel-attendance-hub.',
            category: 'HR',
            icon: 'heroicon-o-clock',
            isCore: false,
            dependencies: ['employees'],
            features: ['Device Sync Driver', 'Simulation Mode for Demos', 'Daily Hours Calculator', 'Late & Absent Tracking']
        ));

        $this->register(new ModuleDefinition(
            id: 'ai_assistant',
            name: 'AI Business Assistant',
            description: 'Multi-provider AI SDK (OpenAI, Gemini, Claude, DeepSeek) for drafting quotes, proposals, and email follow-ups.',
            category: 'AI & Automation',
            icon: 'heroicon-o-sparkles',
            isCore: false,
            dependencies: [],
            features: ['AI Quotation Drafter', 'AI Scope of Work Writer', 'Follow-up Email Generator', 'Multi-provider Configuration']
        ));

        $this->register(new ModuleDefinition(
            id: 'automation',
            name: 'Workflow Automation',
            description: 'Automate business actions with trigger-condition-action rules (e.g. quote accepted -> auto invoice).',
            category: 'Operations',
            icon: 'heroicon-o-bolt',
            isCore: false,
            dependencies: [],
            features: ['Event Trigger Hooks', 'Action Execution Engine', 'Audit Logs']
        ));
    }
}
