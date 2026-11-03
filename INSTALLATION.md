# Installation Guide
## Quotation and Proposal Builder: Send, Sign, Convert to Invoice

This guide provides step-by-step instructions to set up, configure, and run the platform in local development, staging, and production environments.

---

## 1. System Requirements

Ensure your server or local environment meets the following specifications:

| Component | Minimum Version | Recommended Version |
|---|---|---|
| **PHP** | 8.2 | 8.3 / 8.5 |
| **Composer** | 2.5+ | 2.7+ |
| **Node.js** | 18+ | 20+ / 22+ |
| **NPM** | 9+ | 10+ |
| **Database** | SQLite 3.35+, MySQL 8.0+, MariaDB 10.5+, or PostgreSQL 14+ | MySQL 8.0 / PostgreSQL 16 |
| **PHP Extensions** | `bcmath`, `curl`, `fileinfo`, `mbstring`, `openssl`, `pdo`, `tokenizer`, `xml`, `gd` | `bcmath`, `curl`, `gd`, `intl`, `mbstring`, `zip`, `pdo_mysql` / `pdo_pgsql` |

---

## 2. Clone and Dependencies Installation

```bash
# Clone the repository
git clone https://github.com/imrandevbd/quotation-and-proposal-builder-send-sign-convert-to-invoice.git
cd quotation-and-proposal-builder-send-sign-convert-to-invoice

# Install PHP dependencies
composer install --optimize-autoloader

# Install Frontend dependencies and compile assets
npm install
npm run build
```

---

## 3. Environment Configuration

Copy the example environment file and generate a secure application encryption key:

```bash
cp .env.example .env
php artisan key:generate
```

### Database Setup

#### Option A: SQLite (Quickest for Development & Testing)
```env
DB_CONNECTION=sqlite
# database/database.sqlite will be auto-created by Laravel
```

#### Option B: MySQL / MariaDB (Recommended for Production)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quotation_saas
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password
```

#### Option C: PostgreSQL
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=quotation_saas
DB_USERNAME=your_db_user
DB_PASSWORD=your_secure_password
```

---

## 4. Database Migration & Interactive Demo Seeding

Run database migrations along with the complete enterprise demonstration seeder:

```bash
php artisan migrate --seed
```

This populates:
1. **SaaS Subscription Plans:** Starter ($19/mo), Professional ($49/mo), Enterprise ($99/mo), Lifetime ($499 one-time).
2. **Platform SuperAdmin:**
   - URL: `http://localhost:8000/platform`
   - Email: `admin@platform.test`
   - Password: `password`
3. **Demo Business Tenant:** "Demo Business Solutions" (`demo-tenant`)
4. **Tenant Users:**
   - **Company Owner:** `owner@demo.test` / `password`
   - **Sales Executive:** `sales@demo.test` / `password`
   - **Lead Accountant:** `accountant@demo.test` / `password`
5. **Customer Portal Contact:** `client@acmecorp.test`
6. **Pre-configured Data:** Products, product categories, quotation lifecycle samples, signed proposal with SHA-256 legal audit log, converted invoice with partial payment, automation rules, and activity logs.

---

## 5. Storage Symlink & File Permissions

Create the public storage symlink for uploaded logos, brand banners, and digital signature stamps:

```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
```

---

## 6. Running the Development Environment

Start the local PHP development server:

```bash
php artisan serve
```

In a separate terminal, run background queues and scheduled tasks:

```bash
# Queue Worker for email dispatches and PDF generation jobs
php artisan queue:work --tries=3

# Scheduler Worker (or configure Cron in production)
php artisan schedule:work
```

---

## 7. Verifying the Installation

Open your browser and navigate to:
- **Public Homepage & Solutions:** `http://localhost:8000/`
- **1-Click Interactive Demo Switcher:** `http://localhost:8000/#demo-section`
- **Tenant Business Portal:** `http://localhost:8000/app`
- **SuperAdmin Management Panel:** `http://localhost:8000/platform`
- **Public Modules Catalog:** `http://localhost:8000/modules`
- **REST API v1 Documentation:** `http://localhost:8000/docs`

Run the test suite to verify 100% test pass rate:

```bash
php artisan test
```

---

*Architected & Maintained by **Imran Ahmed** (`me@imrandev.bd`) • [https://imrandev.bd](https://imrandev.bd)*

