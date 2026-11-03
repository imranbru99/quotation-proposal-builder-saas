# Changelog
All notable changes to **Quotation and Proposal Builder: Send, Sign, Convert to Invoice** will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.0] - 2026-11-04

### Added
- **Multi-Tenant SaaS Foundation:**
  - Database tenancy architecture supporting thousands of isolated workspaces.
  - Automatic `TenantScope` and `BelongsToTenant` trait enforcing row-level query filtering.
  - `TenantMiddleware` resolving active tenant by authenticated user and context.
  - Platform subscription plans (`Starter`, `Professional`, `Enterprise`, `Lifetime`).

- **Pluggable Module Management Engine:**
  - Centralized `ModuleRegistry` and `ModuleManager`.
  - Dynamic module marketplace inside the tenant dashboard (`/app/module-marketplace`).
  - Zero Data Loss Guarantee: deactivating a module hides routes and navigation while preserving 100% of historical database records.
  - Dependency validation preventing deactivation of prerequisite modules.

- **Quotation Builder & 1-Click Invoice Conversion:**
  - Interactive quotation lifecycle from `draft` to `sent`, `viewed`, `accepted`, `rejected`, and `converted`.
  - Real-time line item subtotal, percentage/fixed discount, and tax calculations.
  - Transactional `QuotationToInvoiceConverter` ensuring atomic conversion into formal invoices (`INV-YYYY-XXXX`).
  - Idempotent conversion guards preventing duplicate invoices upon multiple requests.

- **Modular Proposals & Legal Electronic Signatures:**
  - Rich proposal builder with modular sections, deliverables, scope of work, and payment milestones.
  - Tokenized client review portal (`/portal/proposal/view/{token}`).
  - Dual signature input: responsive HTML5 canvas drawing pad or cryptographic cursive typed signature.
  - Tamper-evident audit log capturing signer IP, User-Agent, UTC timestamp, legal consent acknowledgment, and SHA-256 document checksum.

- **Invoice Billing, Payment Reconciliation & Receipts:**
  - Invoicing engine with balance due tracking and status progression (`draft` -> `sent` -> `partially_paid` -> `paid` -> `overdue`).
  - Modal action for recording payments (Bank Transfer, Credit Card, Cash, Stripe, PayPal, bKash).
  - Automated payment receipt generator with unique sequential receipt numbers.

- **Unicode Multilingual PDF Engine:**
  - Native Unicode PDF service powered by `imrandevbd/laravel-unicode-pdf`.
  - Out-of-the-box support for English, Bangla (complex script shaping), Arabic (RTL text alignment), Hindi, Chinese, and European currencies.
  - Blade templates for Quotations, Proposals, Invoices, and Payment Receipts.

- **Multi-Provider AI Business Assistant:**
  - Integrated via `imrandevbd/laravel-ai-hub` supporting OpenAI, Anthropic Claude, and Google Gemini.
  - AI scope-of-work generation, terms & conditions suggestions, and personalized follow-up email drafting.
  - Intelligent offline template fallbacks ensuring uninterrupted service during network outages.

- **Optional Business Module Packs:**
  - **CRM:** Lead management, deal pipelines, stage tracking.
  - **Projects & Tasks:** Milestones, task assignments, status boards.
  - **Inventory & Warehouses:** Multi-warehouse stock tracking, low-stock reorder thresholds.
  - **Suppliers & Purchase Orders:** Vendor management and procurement tracking.
  - **Human Resources & Attendance:** Employee directory, leave requests, and biometric attendance punch synchronization via `imrandevbd/laravel-attendance-hub`.
  - **Automation Rules:** Configurable workflow triggers and automated document actions.

- **Dual Filament 5 Admin Panels:**
  - Tenant Business Operations Panel (`/app`) for business owners, sales executives, and accountants.
  - Platform SuperAdmin Governance Panel (`/platform`) for SaaS administrators, tenant billing, and plan tier configurations.

- **Customer Self-Service Web Portal:**
  - Secure tokenized portal for clients to review, accept/decline quotations, sign proposals, view invoices, and download PDFs without mandatory registration.

- **REST API v1:**
  - Secure REST API authenticated with Laravel Sanctum tokens.
  - Endpoints for Authentication, Customers, Quotations, and Invoices.
  - 1-Click API endpoint for quotation-to-invoice conversion.

- **Interactive Public Marketing Website:**
  - Modern Tailwind CSS responsive landing page with brand showcases and pricing tiers.
  - Public Modules Catalog (`/modules`) and single module detail pages (`/modules/{slug}`).
  - Solutions by industry (`/solutions`), AI showcase (`/ai-features`), Security & Compliance (`/security`), Documentation (`/docs`), and Contact (`/contact`).
  - Interactive 1-click Demo Role Switcher enabling prospective clients to test SuperAdmin, Company Owner, Sales, and Accountant roles instantly.

- **Testing & Verification:**
  - Comprehensive automated test suite (`tests/Feature/`) with 100% pass rate across Tenancy, Module Activation, Quotation Workflows, Electronic Signatures, Invoices, Customer Portal, REST API, and Public Pages.

---

*Architected & Maintained by **Imran Ahmed** (`me@imrandev.bd`) • [https://imrandev.bd](https://imrandev.bd)*

