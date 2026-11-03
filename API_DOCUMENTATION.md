# REST API v1 Documentation
## Quotation and Proposal Builder: Send, Sign, Convert to Invoice

The REST API allows external systems, mobile apps, CRM integrations, and webhooks to interact with the multi-tenant SaaS platform securely.

---

## 1. Authentication & Base URL

- **Base URL:** `https://your-domain.com/api/v1`
- **Authentication Engine:** Laravel Sanctum Bearer Tokens
- **Required Request Headers:**
  ```http
  Accept: application/json
  Content-Type: application/json
  Authorization: Bearer <YOUR_API_TOKEN>
  ```

---

## 2. Authentication Endpoints

### Login (Obtain Token)
`POST /api/v1/auth/login`

#### Request Payload:
```json
{
  "email": "owner@demo.test",
  "password": "password",
  "device_name": "Mobile Integration"
}
```

#### Response (200 OK):
```json
{
  "message": "Login successful",
  "token": "1|8vjH9a7Kk...L42o0",
  "user": {
    "id": 2,
    "name": "Demo Business Owner",
    "email": "owner@demo.test",
    "role": "owner",
    "tenant_id": 1,
    "company": "Demo Business Solutions"
  }
}
```

---

### Current Authenticated User & Tenant Info
`GET /api/v1/auth/me`

#### Response (200 OK):
```json
{
  "id": 2,
  "name": "Demo Business Owner",
  "email": "owner@demo.test",
  "role": "owner",
  "tenant": {
    "id": 1,
    "name": "Demo Business Solutions",
    "slug": "demo-tenant",
    "currency": "USD"
  }
}
```

---

### Logout (Revoke Token)
`POST /api/v1/auth/logout`

#### Response (200 OK):
```json
{
  "message": "Token revoked successfully"
}
```

---

## 3. Customer Management Endpoints

### List Customers
`GET /api/v1/customers`

#### Response (200 OK):
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "tenant_id": 1,
      "name": "Sarah Connor",
      "company_name": "Acme Global Tech",
      "email": "client@acmecorp.test",
      "phone": "+1-555-0199",
      "type": "company",
      "tax_number": "US-TAX-8921",
      "billing_address": "100 Silicon Blvd, Austin, TX"
    }
  ]
}
```

---

### Create Customer
`POST /api/v1/customers`

#### Request Payload:
```json
{
  "name": "James Vance",
  "company_name": "Vance Refrigeration",
  "type": "company",
  "email": "jvance@vancerefrig.com",
  "phone": "+1-555-0144",
  "billing_address": "12 Corporate Way, Scranton, PA",
  "portal_access_enabled": true
}
```

#### Response (201 Created):
```json
{
  "message": "Customer created successfully",
  "customer": {
    "id": 2,
    "name": "James Vance",
    "company_name": "Vance Refrigeration",
    "email": "jvance@vancerefrig.com"
  }
}
```

---

## 4. Quotations Endpoints

### List Quotations
`GET /api/v1/quotations`

#### Response (200 OK):
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "quotation_number": "QT-2026-0001",
      "title": "Enterprise Cloud Architecture",
      "status": "accepted",
      "currency": "USD",
      "subtotal": "4000.00",
      "tax_amount": "400.00",
      "grand_total": "4400.00",
      "total_amount": 4400.00,
      "issue_date": "2026-11-04",
      "expiry_date": "2026-12-04",
      "customer": {
        "id": 1,
        "name": "Sarah Connor",
        "company_name": "Acme Global Tech"
      }
    }
  ]
}
```

---

### Create Quotation
`POST /api/v1/quotations`

#### Request Payload:
```json
{
  "customer_id": 1,
  "title": "Web Application Modernization",
  "issue_date": "2026-11-04",
  "expiry_date": "2026-12-04",
  "currency": "USD",
  "discount_type": "percentage",
  "discount_value": 5,
  "payment_terms": "50% upfront, 50% upon delivery",
  "delivery_terms": "4 weeks delivery window",
  "notes": "Thank you for partnering with us.",
  "items": [
    {
      "item_name": "API Refactoring & Microservices",
      "description": "Decomposing legacy monolith into modular microservices",
      "quantity": 1,
      "unit_price": 2500.00,
      "tax_percentage": 10.00
    },
    {
      "item_name": "Filament Dashboard Architecture",
      "description": "Custom reporting widgets and role management",
      "quantity": 1,
      "unit_price": 1500.00,
      "tax_percentage": 10.00
    }
  ]
}
```

#### Response (201 Created):
```json
{
  "message": "Quotation created successfully",
  "quotation": {
    "id": 3,
    "quotation_number": "QT-2026-0003",
    "status": "draft",
    "subtotal": "4000.00",
    "discount_amount": "200.00",
    "tax_amount": "400.00",
    "grand_total": "4200.00",
    "total_amount": 4200.00,
    "view_token": "92f75a90-3b8c-4a3e-b8cb-4654876da10f"
  },
  "review_url": "https://your-domain.com/portal/quote/view/92f75a90-3b8c-4a3e-b8cb-4654876da10f"
}
```

---

### 1-Click Convert Quotation to Invoice
`POST /api/v1/quotations/{id}/convert-to-invoice`

#### Request Payload (Optional Overrides):
```json
{
  "due_date": "2026-11-18",
  "notes": "Net 14 payment terms apply."
}
```

#### Response (200 OK):
```json
{
  "message": "Quotation #QT-2026-0001 converted into Invoice #INV-2026-0002 successfully.",
  "invoice": {
    "id": 2,
    "invoice_number": "INV-2026-0002",
    "source_quotation_id": 1,
    "status": "sent",
    "subtotal": "4000.00",
    "grand_total": "4400.00",
    "total_amount": 4400.00,
    "amount_paid": "0.00",
    "balance_due": "4400.00",
    "due_date": "2026-11-18"
  },
  "invoice_url": "https://your-domain.com/portal/invoice/view/4f69ad08-bfad-4566-a36c-df20cfcb6692"
}
```

---

## 5. Invoices Endpoints

### List Invoices
`GET /api/v1/invoices`

### Get Single Invoice
`GET /api/v1/invoices/{id}`

#### Response (200 OK):
```json
{
  "id": 1,
  "invoice_number": "INV-2026-0001",
  "status": "partially_paid",
  "currency": "USD",
  "subtotal": "4000.00",
  "grand_total": "4400.00",
  "amount_paid": "2000.00",
  "balance_due": "2400.00",
  "issue_date": "2026-11-04",
  "due_date": "2026-11-18",
  "customer": {
    "id": 1,
    "name": "Sarah Connor",
    "company_name": "Acme Global Tech"
  },
  "items": [
    {
      "id": 1,
      "item_name": "Enterprise Architecture & Setup",
      "quantity": "1.00",
      "unit_price": "2500.00",
      "total_price": "2750.00"
    }
  ],
  "payments": [
    {
      "id": 1,
      "payment_number": "PAY-2026-0001",
      "amount": "2000.00",
      "payment_method": "bank_transfer",
      "payment_date": "2026-11-04"
    }
  ]
}
```

---

## 6. HTTP Status Codes & Error Formatting

| Code | Meaning | Description |
|---|---|---|
| **200 OK** | Success | Request succeeded. |
| **201 Created** | Resource Created | Resource saved successfully. |
| **401 Unauthorized** | Missing or Invalid Token | Bearer token is missing, expired, or invalid. |
| **403 Forbidden** | Access Denied | Account is inactive or does not possess required role permissions. |
| **404 Not Found** | Resource Not Found | Record does not exist within the authenticated tenant's scope. |
| **422 Unprocessable Entity** | Business Logic or Validation Error | Input failed validation or business guard prevented execution (e.g. converting a rejected quotation). |

#### Validation Error Format:
```json
{
  "message": "The customer id field is required.",
  "errors": {
    "customer_id": [
      "The customer id field is required."
    ]
  }
}
```

---

*Architected & Maintained by **Imran Ahmed** (`me@imrandev.bd`) • [https://imrandev.bd](https://imrandev.bd)*

