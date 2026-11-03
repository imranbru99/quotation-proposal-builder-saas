# Quotation and Proposal Builder: Send, Sign, Convert to Invoice

[![Laravel Framework](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![Filament Admin](https://img.shields.io/badge/Filament-5.x-FFA500?style=for-the-badge&logo=filament&logoColor=white)](https://filamentphp.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.3%20%7C%208.4%20%7C%208.5-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg?style=for-the-badge)](LICENSE)
[![Developer](https://img.shields.io/badge/Developer-Imran%20Ahmed-informational?style=for-the-badge&logo=codeforces&logoColor=white)](https://imrandev.bd)

An enterprise-grade, multi-tenant SaaS platform built with **Laravel 12**, **Filament 5**, **Livewire 4**, and **Tailwind CSS**. Designed for freelancers, digital agencies, consultancies, contractors, and trading businesses to create professional quotations and proposals, collect legally sound electronic signatures, convert accepted deals into invoices with 100% financial accuracy, and manage complete business operations through a modular feature architecture.

- **Developer:** Imran Ahmed (<me@imrandev.bd>)
- **Official Website:** [https://imrandev.bd](https://imrandev.bd)
- **Repository:** Quotation and Proposal Builder: Send, Sign, Convert to Invoice

---

## Table of Contents

1. [Application Overview](#1-application-overview)
2. [Key Features](#2-key-features)
3. [Module Catalog & Extensibility](#3-module-catalog--extensibility)
4. [System Requirements](#4-system-requirements)
5. [Installation & Setup](#5-installation--setup)
6. [Composer Dependencies](#6-composer-dependencies)
7. [Environment Configuration](#7-environment-configuration)
8. [Database Setup & Migrations](#8-database-setup--migrations)
9. [Database Seeding](#9-database-seeding)
10. [Demo Credentials & Interactive Sandbox](#10-demo-credentials--interactive-sandbox)
11. [Filament Panels & Navigation](#11-filament-panels--navigation)
12. [Module Activation Engine](#12-module-activation-engine)
13. [AI Provider Configuration (`laravel-ai-hub`)](#13-ai-provider-configuration)
14. [Multilingual Unicode PDF Engine (`laravel-unicode-pdf`)](#14-multilingual-unicode-pdf-engine)
15. [Attendance Hub Integration (`laravel-attendance-hub`)](#15-attendance-hub-integration)
16. [Queue & Background Workers](#16-queue--background-workers)
17. [Task Scheduler](#17-task-scheduler)
18. [REST API Framework](#18-rest-api-framework)
19. [Deployment Instructions](#19-deployment-instructions)
20. [Backup & Recovery](#20-backup--recovery)
21. [Troubleshooting & Diagnostics](#21-troubleshooting--diagnostics)
22. [Author & License](#22-author--license)

---

## 1. Application Overview

Unlike simple single-tenant CRUD quotation generators, this platform operates as a multi-tenant business operating system where **quotations and proposals** serve as the transactional foundation:

```
[Customer Inquiries / Leads]
            ↓
[AI-Assisted Quotation / Proposal Builder]
            ↓
[Interactive Client Review Portal]
            ↓
[Tamper-Proof Electronic Signature (SHA-256 Audit Trail)]
            ↓
[1-Click Transactional Invoice Conversion (Idempotent Snapshot)]
            ↓
[Partial/Full Payment Tracking & Receipts]
```

Businesses can activate or deactivate modules dynamically (CRM, Inventory, Projects, Expenses, HR, Biometric Attendance, Financials) without touching code or losing historical records.

---

## 2. Key Features

- **Multi-Tenant SaaS Engine:** Shared database with strict `tenant_id` Eloquent scoping (`TenantScope`), tenant-aware middleware, policies, and file storage.
- **Dynamic Module Manager:** Central registry enabling tenants to activate only what they need. Disabling a module never drops or deletes database tables or historical rows.
- **Quotation Builder:** Full status lifecycle (`draft`, `sent`, `accepted`, `rejected`, `converted`, `expired`), item line calculations, multi-currency, custom taxes, discounts, and expiration dates.
- **Proposal Builder:** Rich modular sections (Executive Summary, Scope of Work, Deliverables, Payment Milestones, Terms & Conditions, Portfolio).
- **Electronic Signatures:** Public tokenized signing page with legal intent consent checkbox, typed/drawn signature canvas, IP/UA stamping, and SHA-256 document hashing.
- **Invoice Conversion:** 1-Click quotation-to-invoice conversion wrapped in database transactions, capturing immutable financial snapshots with duplicate conversion prevention.
- **Payments & Receipts:** Multiple partial payment installments, balance tracking, and printable receipts.
- **Customer Portal:** Dedicated tokenized client portal to review documents, accept/decline, sign, and pay invoices.
- **Multilingual Unicode PDF:** Native Arabic RTL, Bengali, and international Latin PDF generation via `imrandevbd/laravel-unicode-pdf`.
- **AI Business Assistant:** Multi-provider AI SDK (`imrandevbd/laravel-ai-hub`) for automatic quotation drafting, proposal generation, and email follow-ups.
- **Biometric Attendance Hub:** Universal driver integration (`imrandevbd/laravel-attendance-hub`) supporting physical terminals and simulated punches.
- **Performance Optimization:** Built-in speed monitoring and query optimization powered by `imrandevbd/drutogoti`.
- **Universal Slugs:** Multilingual SEO URL generation via `imrandevbd/laravel-universal-slug`.

---

## 3. Module Catalog & Extensibility

| Module ID | Module Name | Category | Core / Optional | Dependencies |
|---|---|---|---|---|
| `customers` | Customer & CRM | Core Business | Core | None |
| `products` | Product & Service Catalog | Core Business | Core | None |
| `quotations` | Quotation Builder | Sales | Core | `customers`, `products` |
| `proposals` | Proposal Builder | Sales | Core | `customers` |
| `esignature` | Electronic Signatures | Legal / Sales | Core | `quotations` or `proposals` |
| `invoices` | Invoice Management | Finance | Core | `customers`, `products` |
| `payments` | Payments & Receipts | Finance | Core | `invoices` |
| `crm` | CRM & Deal Pipelines | Sales | Optional | `customers` |
| `inventory` | Inventory Management | Operations | Optional | `products` |
| `purchases` | Purchase Orders & Suppliers| Operations | Optional | `products` |
| `projects` | Project & Task Management | Delivery | Optional | `customers` |
| `expenses` | Expense Management | Finance | Optional | None |
| `employees` | Employee & HR Management | HR | Optional | None |
| `attendance` | Attendance Hub | HR | Optional | `employees` |
| `financials` | Financial Accounts & Ledger| Finance | Optional | `invoices`, `payments` |
| `ai_assistant`| AI Business Assistant | AI & Automation | Optional | None |
| `automation` | Business Workflow Automation| Operations | Optional | None |

---

## 4. System Requirements

- **PHP:** >= 8.3 (tested on PHP 8.5.4)
- **Extensions:** `bcmath`, `curl`, `dom`, `fileinfo`, `intl`, `json`, `mbstring`, `openssl`, `pdo`, `pdo_sqlite` / `pdo_mysql` / `pdo_pgsql`, `redis`, `zip`
- **Database:** SQLite 3.40+, MySQL 8.0+, MariaDB 10.11+, or PostgreSQL 15+
- **Composer:** >= 2.5
- **Node.js & NPM:** Node >= 20.x, npm >= 10.x

---

## 5. Installation & Setup

```bash
# Clone the repository
git clone https://github.com/imranbru99/quotation-and-proposal-builder-send-sign-convert-to-invoice.git
cd quotation-and-proposal-builder-send-sign-convert-to-invoice

# Configure Git Author Identity
git config user.name "Imran Ahmed"
git config user.email "me@imrandev.bd"

# Install PHP dependencies
composer install

# Copy environment configuration
cp .env.example .env

# Generate application key
php artisan key:generate

# Run migrations and seed complete demo environment
php artisan migrate --seed

# Install frontend dependencies and build assets
npm install
npm run build

# Start local development server
php artisan serve
```

---

## 6. Composer Dependencies

The application leverages verified, production-grade packages:
- `filament/filament` (^5.11): Primary administration and business dashboard suite.
- `livewire/livewire` (^4.4): Reactive UI components.
- `imrandevbd/drutogoti`: Automatic bottleneck detection and database optimization.
- `imrandevbd/laravel-admin-panel`: Unified dashboard foundations and widgets.
- `imrandevbd/laravel-ai-hub` (^1.7): Multi-provider AI SDK.
- `imrandevbd/laravel-ai-catalog`: AI catalog discovery, classification, and GEO feed generator.
- `imrandevbd/laravel-attendance-hub`: Device-neutral biometric attendance engine.
- `imrandevbd/laravel-unicode-pdf`: Multilingual Unicode PDF document engine.
- `imrandevbd/laravel-universal-slug`: Multilingual slug generator with collision prevention.

---

## 7. Environment Configuration

Key configuration parameters in `.env`:

```ini
APP_NAME="Quotation & Proposal Builder"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database (SQLite default for instant test/run, MySQL/PgSQL supported)
DB_CONNECTION=sqlite
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=quotation_saas
# DB_USERNAME=root
# DB_PASSWORD=

# Cache & Queues
CACHE_STORE=database
QUEUE_CONNECTION=database
SESSION_DRIVER=database

# AI Hub Configuration
AI_HUB_DEFAULT_PROVIDER=openai
OPENAI_API_KEY=
GEMINI_API_KEY=
ANTHROPIC_API_KEY=
DEEPSEEK_API_KEY=

# Unicode PDF Settings
UNICODE_PDF_ENGINE=browsershot
UNICODE_PDF_DEFAULT_FONT=noto-sans
```

---

## 8. Database Setup & Migrations

```bash
# Run fresh migrations
php artisan migrate:fresh

# Verify migration status
php artisan migrate:status
```

The schema features tenant-isolated tables with foreign key cascades, transactional indexing, and UUID public link tokens for secure document sharing.

---

## 9. Database Seeding

```bash
# Seed platform plans, demo tenant, users, customers, and transactions
php artisan db:seed
```

The seeder initializes:
- 4 SaaS Subscription Plans (Free, Starter, Pro, Enterprise)
- 1 Platform SuperAdmin user
- 1 Complete Demo Tenant ("Demo Business Solutions")
- 4 Role-based Demo Accounts (Owner, Sales Rep, Accountant, Customer)
- Realistic catalog items, quotations, signed proposals, converted invoices, and payments.

---

## 10. Demo Credentials & Interactive Sandbox

| Role | Email | Password | Access Area |
|---|---|---|---|
| **Platform SuperAdmin** | `admin@platform.test` | `password` | `/platform` |
| **Business Owner** | `owner@demo.test` | `password` | `/app` |
| **Sales Executive** | `sales@demo.test` | `password` | `/app` |
| **Finance / Accountant**| `accountant@demo.test` | `password` | `/app` |
| **Client / Customer** | `client@acmecorp.test`| `password` | `/portal` |

The public landing page features a **1-Click Demo Launcher** that logs you into any of these roles instantly.

---

## 11. Filament Panels & Navigation

The platform provides two isolated Filament panels:

1. **Platform SuperAdmin Panel (`/platform`):**
   - Tenant overview, business status, subscription plan editor, global AI configuration, module marketplace catalog, and system health widgets.
2. **Tenant Business Panel (`/app`):**
   - Business dashboard, Quotations, Proposals, Invoices, Customers, Products, Payments, and dynamically registered optional module resources.

---

## 12. Module Activation Engine

Tenants can activate or deactivate modules under **App Settings > Modules Marketplace**:
- **Activate:** Immediately mounts resources in navigation, registers routes, and enables API endpoints.
- **Deactivate:** Safely detaches navigation and guards routes. **Historical records, past quotations, and invoices remain intact.**

---

## 13. AI Provider Configuration

Powered by `imrandevbd/laravel-ai-hub`:
```bash
php artisan ai-hub:configure
```
Supports OpenAI (`gpt-4o`, `gpt-4o-mini`), Google Gemini (`gemini-1.5-pro`, `gemini-1.5-flash`), Anthropic (`claude-3-5-sonnet`), DeepSeek, Groq, and local Ollama models.

---

## 14. Multilingual Unicode PDF Engine

Powered by `imrandevbd/laravel-unicode-pdf`:
- Install Noto fonts: `php artisan unicode-pdf:font:install`
- Inspect font diagnostics: `php artisan unicode-pdf:diagnose`
- Generates high-fidelity quotations and invoices in English, Bengali (বাংলা), and Arabic RTL (العربية) with compliant currency and tax displays.

---

## 15. Attendance Hub Integration

Powered by `imrandevbd/laravel-attendance-hub`:
- Connect physical ZKTeco / biometric devices or run simulated punches:
```bash
php artisan attendance:simulate --count=20
php artisan attendance:sync
```

---

## 16. Queue & Background Workers

Processes PDF generation, email notifications, and automated conversion jobs:
```bash
php artisan queue:work --tries=3 --timeout=90
```

---

## 17. Task Scheduler

Add this cron entry to your production server:
```cron
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```
Automates overdue invoice notifications, recurring invoice generation, and attendance synchronization.

---

## 18. REST API Framework

All endpoints accept and return JSON under `/api/v1` with Bearer token authentication:
- `POST /api/v1/auth/login`
- `GET /api/v1/quotations`
- `POST /api/v1/quotations`
- `POST /api/v1/quotations/{id}/convert-to-invoice`
- `GET /api/v1/invoices`
- `GET /api/v1/customers`

---

## 19. Deployment Instructions

### Production Deployment Steps:
1. Ensure PHP 8.3+ and MySQL 8.0+ / PostgreSQL 15+ are installed.
2. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.
3. Run optimization commands:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   php artisan event:cache
   ```
4. Configure Supervisor for queue workers.
5. Set web root to `/public`.

---

## 20. Backup & Recovery

- Daily database dumps via MySQLdump or PgDump.
- Encrypted file backup of `/storage/app/public` (logos, uploaded signatures, and PDFs).

---

## 21. Troubleshooting & Diagnostics

- **Font rendering issues in PDF:** Run `php artisan unicode-pdf:clear-cache` and `php artisan unicode-pdf:diagnose`.
- **Performance bottleneck:** Check `php artisan drutogoti:audit` (powered by `imrandevbd/drutogoti`).
- **Slugs out of sync:** Run `php artisan slug:regenerate`.

---

## 22. Author & License

- **Developer:** Imran Ahmed
- **Email:** <me@imrandev.bd>
- **Website:** [https://imrandev.bd](https://imrandev.bd)
- **License:** Open-source software licensed under the [MIT License](LICENSE).
