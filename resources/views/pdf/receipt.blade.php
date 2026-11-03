<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Receipt {{ $payment->receipt_number }}</title>
    <style>
        body {
            font-family: 'Noto Sans', 'DejaVu Sans', sans-serif;
            color: #1e293b;
            font-size: 13px;
            line-height: 1.5;
            margin: 0;
            padding: 30px;
        }
        .receipt-card {
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            padding: 24px;
            background: #ffffff;
        }
        .header {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #10b981;
            padding-bottom: 16px;
            margin-bottom: 20px;
        }
        .amount-box {
            background-color: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 6px;
            padding: 16px;
            text-align: center;
            margin: 20px 0;
        }
        .amount-val {
            font-size: 28px;
            font-weight: bold;
            color: #065f46;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        .details-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #f1f5f9;
        }
    </style>
</head>
<body>
    <div class="receipt-card">
        <div class="header">
            <div>
                <div style="font-size: 20px; font-weight: bold;">{{ $tenant->name ?? 'Company' }}</div>
                <div>Official Payment Receipt</div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 16px; font-weight: bold; color: #10b981;">PAID</div>
                <div>Receipt #: <strong>{{ $payment->receipt_number }}</strong></div>
                <div>Date: {{ $payment->payment_date->format('F d, Y') }}</div>
            </div>
        </div>

        <div class="amount-box">
            <div style="font-size: 12px; color: #047857; text-transform: uppercase; font-weight: bold;">Amount Received</div>
            <div class="amount-val">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</div>
        </div>

        <table class="details-table">
            <tr>
                <td style="width: 30%;"><strong>Received From:</strong></td>
                <td>{{ $customer->name ?? 'Client' }} ({{ $customer->company_name ?? '' }})</td>
            </tr>
            <tr>
                <td><strong>Invoice Reference:</strong></td>
                <td>Invoice #{{ $invoice->invoice_number }}</td>
            </tr>
            <tr>
                <td><strong>Payment Method:</strong></td>
                <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
            </tr>
            @if($payment->transaction_reference)
                <tr>
                    <td><strong>Transaction Ref:</strong></td>
                    <td>{{ $payment->transaction_reference }}</td>
                </tr>
            @endif
            <tr>
                <td><strong>Invoice Remaining Balance:</strong></td>
                <td><strong>{{ $payment->currency }} {{ number_format($invoice->balance_due, 2) }}</strong></td>
            </tr>
            @if($payment->notes)
                <tr>
                    <td><strong>Notes:</strong></td>
                    <td>{{ $payment->notes }}</td>
                </tr>
            @endif
        </table>

        <div style="margin-top: 30px; text-align: center; color: #64748b; font-size: 11px;">
            Thank you for your business. This is a computer-generated receipt and requires no physical signature.
        </div>
    </div>
</body>
</html>
