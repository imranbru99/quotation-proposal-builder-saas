<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: 'Noto Sans', 'DejaVu Sans', sans-serif;
            color: #1e293b;
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
            padding: 24px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 24px;
        }
        .company-title {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }
        .doc-title {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
            text-align: right;
            text-transform: uppercase;
        }
        .doc-number {
            font-size: 14px;
            color: #64748b;
            text-align: right;
        }
        .parties {
            display: flex;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .party-box {
            width: 48%;
        }
        .party-label {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 4px;
        }
        .party-name {
            font-size: 15px;
            font-weight: bold;
            color: #0f172a;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .items-table th {
            background-color: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-align: left;
            padding: 10px 12px;
            border-bottom: 1px solid #cbd5e1;
            font-size: 11px;
            text-transform: uppercase;
        }
        .items-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #f1f5f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 24px;
        }
        .totals-table {
            width: 45%;
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals-table td {
            padding: 6px 12px;
        }
        .grand-total {
            background-color: #f0fdf4;
            font-size: 15px;
            font-weight: bold;
            color: #166534;
        }
        .balance-due {
            background-color: #fef2f2;
            font-size: 16px;
            font-weight: bold;
            color: #991b1b;
            border-top: 2px solid #ef4444;
        }
        .terms-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 16px;
            margin-top: 24px;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #dcfce7;
            color: #166534;
        }
        .payments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            font-size: 12px;
        }
        .payments-table th, .payments-table td {
            padding: 6px 8px;
            border-bottom: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="company-title">{{ $tenant->name ?? 'Company Name' }}</div>
            <div>{{ $tenant->company_email ?? '' }} | {{ $tenant->company_phone ?? '' }}</div>
            <div>{{ $tenant->address ?? '' }} {{ $tenant->city ?? '' }} {{ $tenant->country ?? '' }}</div>
            @if(!empty($tenant->tax_id))
                <div>Tax ID: {{ $tenant->tax_id }}</div>
            @endif
        </div>
        <div>
            <div class="doc-title">Tax Invoice</div>
            <div class="doc-number">#{{ $invoice->invoice_number }}</div>
            <div style="margin-top: 6px;">
                <span class="status-badge">{{ strtoupper($invoice->status) }}</span>
            </div>
        </div>
    </div>

    <div class="parties">
        <div class="party-box">
            <div class="party-label">Billed To:</div>
            <div class="party-name">{{ $customer->name ?? 'Valued Client' }}</div>
            @if(!empty($customer->company_name))
                <div>{{ $customer->company_name }}</div>
            @endif
            <div>{{ $customer->email ?? '' }} | {{ $customer->phone ?? '' }}</div>
            <div>{{ $customer->billing_address ?? '' }}</div>
            @if(!empty($customer->tax_number))
                <div>Client Tax ID: {{ $customer->tax_number }}</div>
            @endif
        </div>
        <div class="party-box" style="text-align: right;">
            <div class="party-label">Invoice Details:</div>
            <div><strong>Issue Date:</strong> {{ $invoice->issue_date?->format('F d, Y') ?? now()->format('F d, Y') }}</div>
            <div><strong>Due Date:</strong> {{ $invoice->due_date?->format('F d, Y') ?? 'Due on Receipt' }}</div>
            <div><strong>Currency:</strong> {{ $invoice->currency }}</div>
            @if($invoice->sourceQuotation)
                <div><strong>Ref Quote:</strong> #{{ $invoice->sourceQuotation->quotation_number }}</div>
            @endif
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Description</th>
                <th class="text-center" style="width: 10%;">Qty</th>
                <th class="text-right" style="width: 15%;">Unit Price</th>
                <th class="text-right" style="width: 10%;">Tax</th>
                <th class="text-right" style="width: 15%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->item_name }}</strong>
                        @if($item->description)
                            <div style="color: #64748b; font-size: 11px;">{{ $item->description }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($item->quantity, 2) }}</td>
                    <td class="text-right">{{ $invoice->currency }} {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->tax_percentage, 1) }}%</td>
                    <td class="text-right"><strong>{{ $invoice->currency }} {{ number_format($item->total_price, 2) }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">No line items found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            @if($invoice->discount_amount > 0)
                <tr>
                    <td>Discount:</td>
                    <td class="text-right">-{{ $invoice->currency }} {{ number_format($invoice->discount_amount, 2) }}</td>
                </tr>
            @endif
            @if($invoice->tax_amount > 0)
                <tr>
                    <td>Tax:</td>
                    <td class="text-right">+{{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}</td>
                </tr>
            @endif
            <tr class="grand-total">
                <td>Grand Total:</td>
                <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->grand_total, 2) }}</td>
            </tr>
            <tr>
                <td>Amount Paid:</td>
                <td class="text-right" style="color: #059669;">-{{ $invoice->currency }} {{ number_format($invoice->amount_paid, 2) }}</td>
            </tr>
            <tr class="balance-due">
                <td>Balance Due:</td>
                <td class="text-right">{{ $invoice->currency }} {{ number_format($invoice->balance_due, 2) }}</td>
            </tr>
        </table>
    </div>

    @if($payments->isNotEmpty())
        <div style="margin-top: 20px;">
            <div style="font-size: 13px; font-weight: bold; margin-bottom: 6px;">Recorded Payments & Installments</div>
            <table class="payments-table">
                <thead>
                    <tr style="background-color: #f1f5f9;">
                        <th>Date</th>
                        <th>Receipt #</th>
                        <th>Method</th>
                        <th>Reference</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $p)
                        <tr>
                            <td>{{ $p->payment_date->format('M d, Y') }}</td>
                            <td>{{ $p->receipt_number }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $p->payment_method)) }}</td>
                            <td>{{ $p->transaction_reference ?? '-' }}</td>
                            <td class="text-right"><strong>{{ $p->currency }} {{ number_format($p->amount, 2) }}</strong></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if(!empty($invoice->notes) || !empty($invoice->payment_terms) || !empty($invoice->terms_and_conditions))
        <div class="terms-box">
            @if(!empty($invoice->payment_terms))
                <div><strong>Payment Terms:</strong> {{ $invoice->payment_terms }}</div>
            @endif
            @if(!empty($invoice->notes))
                <div style="margin-top: 4px;"><strong>Notes:</strong> {{ $invoice->notes }}</div>
            @endif
            @if(!empty($invoice->terms_and_conditions))
                <div style="margin-top: 6px; font-size: 11px; color: #64748b;">
                    <strong>Terms & Conditions:</strong> {{ $invoice->terms_and_conditions }}
                </div>
            @endif
        </div>
    @endif
</body>
</html>
