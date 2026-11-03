# SuperAdmin & Platform Administrator Guide
## Quotation and Proposal Builder: Send, Sign, Convert to Invoice

This guide details the administration, governance, and operational procedures for Platform SuperAdministrators operating the multi-tenant SaaS infrastructure.

---

## 1. Platform SuperAdmin Access

- **Panel URL:** `http://your-domain.com/platform`
- **Default SuperAdmin Email:** `admin@platform.test`
- **Default Password:** `password`
- **Access Guard:** Governed by `is_platform_superadmin = true` on the `users` table.

---

## 2. Multi-Tenant Account Governance

Platform SuperAdministrators have oversight across all independent tenant workspaces without compromising tenant data integrity.

### Viewing & Managing Tenants
1. Navigate to **Tenants Management** inside `/platform`.
2. Review company workspaces:
   - Tenant Name, Slug, Custom Domain, Plan Assignment.
   - Subscription Status (`active`, `past_due`, `suspended`, `cancelled`).
   - Creation Date, User Count, Active Module Count.
3. **Suspending a Tenant:**
   - Setting a tenant status to `suspended` immediately prevents tenant users and customers from accessing `/app` and `/portal` endpoints via `TenantMiddleware`.
   - All tenant data remains preserved and isolated.
4. **Impersonation & Support:**
   - SuperAdmins can launch support sessions to troubleshoot tenant-specific configuration or module issues.

---

## 3. SaaS Subscription Plans & Feature Entitlements

Configure monetization packages:

1. Navigate to **Subscription Plans** (`/platform/plans`).
2. Manage plan tiers:
   - **Starter ($19/mo):** Quotations, Invoices, Proposals, Electronic Signatures, PDF Exports.
   - **Professional ($49/mo):** Starter + CRM, Projects, Expenses, Inventory, Automation Engine.
   - **Enterprise ($99/mo):** Professional + Biometric Attendance Hub, Multi-warehouse, AI Assistant, Dedicated Webhooks.
   - **Lifetime Deal ($499 one-time):** Lifetime license package with selectable module quotas.
3. Setting Module Entitlements:
   - Define which modules are included in each plan tier.
   - When a tenant subscribes, their available modules in the `/app/module-marketplace` are automatically gated according to their plan limits.

---

## 4. Platform Module Registry & Feature Flags

SuperAdmins maintain central control over all business modules:

- Located in `App\Services\Modules\ModuleRegistry`.
- Modules can be globally enabled, deprecated, or restricted platform-wide.
- Dependencies are validated before allowing changes:
  - For example, `quotations` depends on `customers` and `products`.
  - `invoices` depends on `customers`.
  - Disabling a prerequisite platform-wide displays dependency warnings.

---

## 5. Multi-Provider AI Configuration

AI capabilities are powered by `imrandevbd/laravel-ai-hub`:

1. **Config File:** `config/ai-hub.php` (or configured via Platform Settings).
2. Supported Providers:
   - OpenAI (GPT-4o, GPT-3.5-Turbo)
   - Anthropic (Claude 3.5 Sonnet)
   - Google Gemini (Gemini 1.5 Pro/Flash)
   - Local / Self-Hosted LLMs (Ollama, vLLM)
3. **Environment Keys:**
   ```env
   AI_HUB_DEFAULT_PROVIDER=openai
   OPENAI_API_KEY=sk-...
   ANTHROPIC_API_KEY=sk-ant-...
   GEMINI_API_KEY=AIza...
   ```
4. **Safety & Fallback:**
   - The platform includes intelligent offline template fallbacks in `AiAssistantService.php` to ensure zero disruption even if external API limits or network timeouts occur.

---

## 6. Biometric Attendance Machine Integration

The platform integrates with `imrandevbd/laravel-attendance-hub` for enterprise workforce tracking:

1. **Webhook Endpoint:** `POST /api/v1/attendance/punch`
2. **Supported Protocols:** ZKTeco ADMS, standalone HTTP JSON punch hooks, RFID badge readers.
3. **Payload Structure:**
   ```json
   {
       "device_id": "DEV-AUSTIN-01",
       "employee_code": "EMP-001",
       "punch_time": "2026-11-04 09:00:15",
       "punch_type": "in",
       "verification_mode": "fingerprint"
   }
   ```
4. Attendance punches are mapped automatically to the tenant's employee records and calculate daily working hours and overtime.

---

## 7. Performance & Optimization Management

Optimized using `imrandevbd/drutogoti`:

- Accelerates database query pipelines and response caching.
- To clear and warm up production caches:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan event:cache
  ```

---

## 8. Platform Audit & Compliance Monitoring

- View tenant activity timelines in real time.
- All critical mutations (conversions, electronic signature captures, balance adjustments, plan upgrades) log:
  - Tenant ID, User ID, Action, IP Address, User-Agent, and detailed JSON payload snapshots.
- Stored in the `activity_logs` table for compliance audit readiness.

---

*Architected & Maintained by **Imran Ahmed** (`me@imrandev.bd`) • [https://imrandev.bd](https://imrandev.bd)*

