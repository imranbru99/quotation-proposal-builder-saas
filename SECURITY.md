# Security & Compliance Policy
## Quotation and Proposal Builder: Send, Sign, Convert to Invoice

Data protection, tenant isolation, and cryptographic integrity are foundational design pillars of this platform.

---

## 1. Multi-Tenant Data Isolation

The application enforces strict data segregation across all shared infrastructure:

1. **Global Tenant Scopes:** Every tenant-bound Eloquent model implements the `BelongsToTenant` trait, which binds a global `TenantScope`. All `select`, `update`, and `delete` queries automatically inject `WHERE tenant_id = ?` matching the authenticated tenant's identifier.
2. **Context Resolution:** The active tenant is resolved at the HTTP boundary via `TenantMiddleware`. If a user attempts to spoof or query resources belonging to another tenant, the query returns `404 Not Found`.
3. **Database Constraints:** Foreign keys strictly cascade to tenant records, ensuring referential integrity and preventing orphaned data across tenants.

---

## 2. Electronic Signatures & Cryptographic Audit Trail

The Electronic Signature module is engineered to satisfy the standards of the **US ESIGN Act**, **UETA**, and **EU eIDAS (SES)** regulations:

1. **Document Checksum (SHA-256):** When a proposal or agreement is signed, an immutable SHA-256 hash is computed across all milestone amounts, line items, and contractual clauses. Any subsequent attempt to alter the record invalidates the checksum.
2. **Legal Consent Recording:** Signers are required to explicitly accept affirmative legal consent checkboxes before submitting signatures.
3. **Audit Trail Capture:** The system captures and cryptographically stores:
   - Signer legal full name and verified email.
   - Exact UTC timestamp of submission.
   - Signer IP address (`REMOTE_ADDR`) and Client User-Agent string.
   - Vector signature stroke data (HTML5 canvas) or high-fidelity cryptographic cursive signature.
4. **Document Status Lock:** Once signed, the document transitions to `signed` status and its financial, milestone, and section contents are strictly read-only.

---

## 3. Authentication & API Security

1. **Password Hashing:** Passwords are encrypted using Bcrypt with configurable work factors.
2. **REST API Authentication:** Powered by Laravel Sanctum using cryptographically secure personal access tokens. Tokens are stored hashed in the database.
3. **Brute Force Protection:** Authentication endpoints and customer review portals are rate-limited using Laravel's rate limiter middleware (e.g. 5 attempts per minute).
4. **Tokenized Portal Security:** Customer review links for quotes, invoices, and proposals use unguessable 128-bit version-4 UUID tokens (`view_token`), preventing enumeration attacks.

---

## 4. Input Sanitization & Threat Mitigations

1. **SQL Injection:** All database operations utilize PDO prepared statements and Eloquent ORM parameter bindings. Raw SQL queries are prohibited.
2. **Cross-Site Scripting (XSS):** Blade templates automatically escape all output with `htmlspecialchars`. Sensitive signature rendering is strictly validated against base64 image and SVG constraints.
3. **Cross-Site Request Forgery (CSRF):** All state-changing web requests require valid CSRF tokens verified by `VerifyCsrfToken` middleware.
4. **Secure File Storage:** User uploads (company logos, signature stamps) undergo strict MIME-type validation, size limits, and are stored with randomized UUID filenames outside the public PHP execution scope.

---

## 5. Security Vulnerability Reporting

If you identify a security vulnerability in this project, please report it responsibly:

- **Security Contact:** Imran Ahmed (`me@imrandev.bd`)
- **Official Website:** [https://imrandev.bd](https://imrandev.bd)
- **Response Timeline:** Acknowledgment within 24 hours; critical fix patches released within 48-72 hours.
- Please do not disclose vulnerabilities publicly until a fix has been released.

---

*Architected & Maintained by **Imran Ahmed** (`me@imrandev.bd`) • [https://imrandev.bd](https://imrandev.bd)*

