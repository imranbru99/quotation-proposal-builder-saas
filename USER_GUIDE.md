# User & Operations Guide
## Quotation and Proposal Builder: Send, Sign, Convert to Invoice

This guide explains how to use the business applications, portals, and features from the perspective of company owners, sales teams, finance managers, and customers.

---

## 1. System Navigation Overview

When logged into the Tenant Business Portal (`/app`), users have access to role-governed navigation groups:

- **Sales & Documents:** Quotations, Proposals, Customers, Products & Services
- **Finance & Billing:** Invoices, Payments, Expenses
- **Operations (Optional):** Projects & Tasks, Inventory & Warehouses, Suppliers & Purchases
- **Human Resources (Optional):** Employees, Biometric Attendance Punches, Leave Requests
- **System & Automation:** Module Marketplace, Automation Rules, Audit Logs, Settings

---

## 2. Customer & Client Directory

Before issuing quotations, create or import your clients:

1. Navigate to **Customers** (`/app/customers`).
2. Click **New Customer**.
3. Fill in:
   - **Entity Type:** Company or Individual.
   - **Name & Company Name:** Primary contact person and business title.
   - **Email & Phone:** Email is used for tokenized notification links and PDF delivery.
   - **Tax / VAT ID:** Displayed automatically on formal invoices and quotes.
   - **Addresses:** Billing address and optional delivery address.
   - **Portal Access:** Enable self-service portal access for the customer.
4. Click **Create Customer**.

---

## 3. Product & Services Catalog

Pre-configure items to speed up quotation generation:

1. Navigate to **Products** (`/app/products`).
2. Click **New Product**.
3. Specify:
   - **Item Type:** Physical Good or Service.
   - **SKU & Universal Slug:** Auto-generated multilingual slug supporting English, Bengali, Arabic, etc.
   - **Unit Price & Cost Price:** Automatically tracks profit margins.
   - **Default Tax Rate:** Associate default VAT/GST rate.
   - **Inventory Threshold:** Receive low-stock alerts if the Inventory module is enabled.

---

## 4. Quotation Lifecycle & 1-Click Conversion

### Step 1: Creating a Quotation
1. Navigate to **Quotations** (`/app/quotations`) -> **New Quotation**.
2. Select your Customer. The currency and default payment terms are inherited automatically.
3. Set the **Issue Date** and **Expiry Date** (e.g. 15 or 30 days).
4. Add **Line Items**:
   - Choose from the Product Catalog or type custom ad-hoc line items.
   - Enter Quantity, Unit Price, and Tax Percentage.
   - Subtotals and tax amounts are calculated dynamically in real time.
5. Apply Discounts:
   - Percentage-based or fixed amount.
6. Add Payment Terms, Delivery Notes, and internal memos.
7. Save as **Draft** or mark as **Sent**.

### Step 2: Sharing with the Client
- Each quotation generates a unique, unguessable **Tokenized Review URL** (e.g., `/portal/quote/view/{view_token}`).
- The client receives this link via email or messaging app.
- They can review the quote on desktop or mobile, download a Unicode PDF, and click **Accept Quotation** or **Decline**.

### Step 3: 1-Click Invoice Conversion
Once accepted:
1. In the Quotation Table or Detail View, click the **Convert to Invoice** button.
2. An interactive modal allows you to set the Invoice Due Date and optional payment instructions.
3. Click **Confirm Conversion**.
4. The system executes an atomic database transaction:
   - Generates a new formal Invoice (`INV-YYYY-XXXX`).
   - Copies exact line item snapshots, tax rates, and discounts.
   - Locks the quotation to prevent duplicate conversion.
   - Logs an audit entry in the tenant's activity log.
   - Triggers any active automation rules.

---

## 5. Rich Proposals & Electronic Signatures

### Building a Proposal
1. Navigate to **Proposals** (`/app/proposals`) -> **New Proposal**.
2. Specify title, currency, and customer.
3. Build modular sections:
   - Executive Summary
   - Scope of Work & Deliverables
   - Technical Specifications & Methodologies
   - Project Timeline & Payment Milestones
   - Terms & Conditions
4. Save and generate the client review link.

### Collecting Legal Electronic Signatures
1. The client opens the secure Proposal Portal (`/portal/proposal/view/{token}`).
2. They review all sections, milestone fees, and legal conditions.
3. At the bottom, they select their signature method:
   - **Draw Signature:** Sign directly on screen using an HTML5 responsive canvas pad.
   - **Type Signature:** Type their legal name rendered in a cursive cryptographic signature style.
4. Check the mandatory legal consent box (*"I acknowledge and agree to be legally bound by this document"*).
5. Click **Sign & Accept Proposal**.
6. The platform locks the proposal:
   - Computes a **SHA-256 document checksum** ensuring immutability.
   - Records the signer's Full Name, Email, IP Address, User-Agent, and Timestamp.
   - Locks the proposal status to `signed`.

---

## 6. Invoice Billing, Payments & Receipts

### Managing Invoices
1. Navigate to **Invoices** (`/app/invoices`).
2. Filter by status: `Draft`, `Sent`, `Partially Paid`, `Paid`, `Overdue`.
3. Invoices display:
   - Total Amount, Amount Paid, and Balance Due.
   - Days until due / Overdue warning badges.
   - 1-Click Multilingual Unicode PDF download.

### Recording Payments
1. Click the **Record Payment** action on any unpaid or partially paid invoice.
2. Enter:
   - Payment Amount (cannot exceed balance due).
   - Payment Method: Bank Transfer, Credit Card, Cash, Stripe, PayPal, bKash, etc.
   - Transaction Reference / Cheque Number.
   - Payment Date & Notes.
3. Click **Save Payment**.
4. The system:
   - Reduces the balance due.
   - Updates invoice status to `paid` if fully cleared or `partially_paid` if balance remains.
   - Generates a formal **Payment Receipt PDF** with an immutable receipt number.

---

## 7. Dynamic Module Marketplace (Zero Data Loss)

Tenants can customize their operational ecosystem at any time:

1. Navigate to **Module Marketplace** (`/app/module-marketplace`).
2. Browse core and optional business modules:
   - **CRM:** Lead management, deal pipelines, conversion tracking.
   - **Project Management:** Tasks, milestones, time logging.
   - **Inventory:** Multi-warehouse tracking, stock reorder levels.
   - **Purchase Orders:** Supplier management, PO workflows.
   - **Employee Attendance:** Biometric punches, work hours, leave tracking.
   - **AI Assistant:** Auto-generating quote scopes, emails, terms.
3. Toggle any module **ON** or **OFF**:
   - Deactivating a module instantly hides its navigation, widgets, and routes.
   - **Zero Data Loss Guarantee:** No database records, transactions, or historical data are ever dropped or deleted.
   - Reactivating the module instantly restores complete access to all existing data.

---

## 8. AI Business Assistant

Leverage integrated AI directly within your workflows:
- **Scope of Work Generator:** Generates professional project deliverables based on customer requirements.
- **Terms & Conditions Enhancer:** Suggests industry-specific payment and liability terms.
- **Email Drafter:** Composes personalized quote follow-ups and payment reminders.
- **Catalog Classifier:** Categorizes new inventory items and generates marketing descriptions.

---

*Architected & Maintained by **Imran Ahmed** (`me@imrandev.bd`) • [https://imrandev.bd](https://imrandev.bd)*
