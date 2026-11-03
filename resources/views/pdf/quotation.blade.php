<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quotation {{ $quotation->quotation_number }}</title>
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
            color: #2563eb;
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
        .meta-table {
            margin-bottom: 24px;
            border-collapse: collapse;
            width: 100%;
        }
        .meta-table td {
            padding: 4px 8px;
            font-size: 12px;
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
            font-size: 16px;
            font-weight: bold;
            color: #166534;
            border-top: 2px solid #22c55e;
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
            background-color: #e0f2fe;
            color: #0369a1;
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
                <div>Tax / VAT ID: {{ $tenant->tax_id }}</div>
            @endif
        </div>
        <div>
            <div class="doc-title">Quotation</div>
            <div class="doc-number">#{{ $quotation->quotation_number }}</div>
            <div style="margin-top: 6px;">
                <span class="status-badge">{{ strtoupper($quotation->status) }}</span>
            </div>
        </div>
    </div>

    <div class="parties">
        <div class="party-box">
            <div class="party-label">Prepared For:</div>
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
            <div class="party-label">Quote Details:</div>
            <div><strong>Issue Date:</strong> {{ $quotation->issue_date?->format('F d, Y') ?? now()->format('F d, Y') }}</div>
            @if($quotation->expiry_date)
                <div><strong>Valid Until:</strong> {{ $quotation->expiry_date->format('F d, Y') }}</div>
            @endif
            <div><strong>Currency:</strong> {{ $quotation->currency }}</div>
        </div>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Item & Description</th>
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
                    <td class="text-right">{{ $quotation->currency }} {{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">{{ number_format($item->tax_percentage, 1) }}%</td>
                    <td class="text-right"><strong>{{ $quotation->currency }} {{ number_format($item->total_price, 2) }}</strong></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 20px;">No items listed in this quotation.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="totals-section">
        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">{{ $quotation->currency }} {{ number_format($quotation->subtotal, 2) }}</td>
            </tr>
            @if($quotation->discount_amount > 0)
                <tr>
                    <td>Discount:</td>
                    <td class="text-right">-{{ $quotation->currency }} {{ number_format($quotation->discount_amount, 2) }}</td>
                </tr>
            @endif
            @if($quotation->tax_amount > 0)
                <tr>
                    <td>Tax:</td>
                    <td class="text-right">+{{ $quotation->currency }} {{ number_format($quotation->tax_amount, 2) }}</td>
                </tr>
            @endif
            <tr class="grand-total">
                <td>Grand Total:</td>
                <td class="text-right">{{ $quotation->currency }} {{ number_format($quotation->grand_total, 2) }}</td>
            </tr>
        </table>
    </div>

    @if(!empty($quotation->notes) || !empty($quotation->payment_terms) || !empty($quotation->terms_and_conditions))
        <div class="terms-box">
            @if(!empty($quotation->payment_terms))
                <div><strong>Payment Terms:</strong> {{ $quotation->payment_terms }}</div>
            @endif
            @if(!empty($quotation->delivery_terms))
                <div style="margin-top: 4px;"><strong>Delivery Terms:</strong> {{ $quotation->delivery_terms }}</div>
            @endif
            @if(!empty($quotation->notes))
                <div style="margin-top: 4px;"><strong>Notes:</strong> {{ $quotation->notes }}</div>
            @endif
            @if(!empty($quotation->terms_and_conditions))
                <div style="margin-top: 6px; font-size: 11px; color: #64748b;">
                    <strong>Terms & Conditions:</strong> {{ $quotation->terms_and_conditions }}
                </div>
            @endif
        </div>
    @endif
</body>
</html>

