@extends('portal.layout')

@section('title', "Invoice #{$invoice->invoice_number}")
@section('header_company', $tenant->name ?? 'Invoice & Billing')

@section('header_actions')
    <a href="{{ route('portal.invoice.pdf', ['token' => $invoice->view_token]) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition">
        <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
        Download PDF
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <!-- Header Banner -->
    <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 to-emerald-950 text-white">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-emerald-400">Tax Invoice</div>
            <h1 class="text-2xl sm:text-3xl font-extrabold mt-1">#{{ $invoice->invoice_number }}</h1>
            <p class="text-slate-300 text-sm mt-1">{{ $invoice->title ?? 'Professional Services & Products' }}</p>
        </div>
        <div class="sm:text-right">
            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                @if($invoice->status === 'paid') bg-emerald-500/20 text-emerald-300 border border-emerald-500/40
                @elseif($invoice->status === 'partially_paid') bg-amber-500/20 text-amber-300 border border-amber-500/40
                @elseif($invoice->status === 'overdue') bg-rose-500/20 text-rose-300 border border-rose-500/40
                @else bg-blue-500/20 text-blue-300 border border-blue-500/40 @endif">
                {{ strtoupper(str_replace('_', ' ', $invoice->status)) }}
            </span>
            <div class="mt-2">
                <div class="text-xs text-slate-300">Balance Due:</div>
                <div class="text-2xl font-black text-white">
                    {{ $invoice->currency }} {{ number_format($invoice->balance_due, 2) }}
                </div>
            </div>
        </div>
    </div>

    <!-- Parties & Dates -->
    <div class="p-6 sm:p-8 grid grid-cols-1 sm:grid-cols-2 gap-8 border-b border-slate-100">
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Billed By</h3>
            <div class="font-bold text-slate-900 text-base">{{ $tenant->name ?? 'Company' }}</div>
            <div class="text-sm text-slate-600 mt-1">{{ $tenant->company_email ?? '' }}</div>
            <div class="text-sm text-slate-600">{{ $tenant->company_phone ?? '' }}</div>
            <div class="text-sm text-slate-500 mt-1">{{ $tenant->address ?? '' }} {{ $tenant->city ?? '' }}</div>
        </div>
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Billed To</h3>
            <div class="font-bold text-slate-900 text-base">{{ $customer->name ?? 'Valued Client' }}</div>
            @if($customer->company_name)
                <div class="text-sm font-medium text-slate-700">{{ $customer->company_name }}</div>
            @endif
            <div class="text-sm text-slate-600 mt-1">{{ $customer->email ?? '' }}</div>
            <div class="text-xs text-slate-500 mt-2">
                <strong>Issue Date:</strong> {{ $invoice->issue_date?->format('M d, Y') ?? now()->format('M d, Y') }}
                <span class="mx-2">•</span> <strong>Due Date:</strong> {{ $invoice->due_date?->format('M d, Y') ?? 'On Receipt' }}
            </div>
        </div>
    </div>

    <!-- Line Items Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-600 text-xs font-semibold uppercase tracking-wider border-b border-slate-200">
                    <th class="py-3 px-6">#</th>
                    <th class="py-3 px-6">Description</th>
                    <th class="py-3 px-4 text-center">Qty</th>
                    <th class="py-3 px-4 text-right">Unit Price</th>
                    <th class="py-3 px-4 text-right">Tax</th>
                    <th class="py-3 px-6 text-right">Total</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($items as $i => $item)
                    <tr>
                        <td class="py-4 px-6 text-slate-400 text-xs">{{ $i + 1 }}</td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-900">{{ $item->item_name }}</div>
                            @if($item->description)
                                <div class="text-xs text-slate-500 mt-0.5">{{ $item->description }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center font-medium">{{ number_format($item->quantity, 2) }}</td>
                        <td class="py-4 px-4 text-right font-medium text-slate-600">{{ $invoice->currency }} {{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-4 px-4 text-right text-xs text-slate-500">{{ number_format($item->tax_percentage, 1) }}%</td>
                        <td class="py-4 px-6 text-right font-bold text-slate-900">{{ $invoice->currency }} {{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Totals & Balances -->
    <div class="p-6 sm:p-8 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row justify-between gap-6">
        <div class="sm:max-w-md text-xs text-slate-500 space-y-2">
            @if($invoice->payment_terms)
                <div><span class="font-semibold text-slate-700">Payment Terms:</span> {{ $invoice->payment_terms }}</div>
            @endif
            @if($invoice->notes)
                <div><span class="font-semibold text-slate-700">Notes:</span> {{ $invoice->notes }}</div>
            @endif
        </div>

        <div class="w-full sm:w-72 space-y-2 text-sm">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal:</span>
                <span class="font-semibold">{{ $invoice->currency }} {{ number_format($invoice->subtotal, 2) }}</span>
            </div>
            @if($invoice->discount_amount > 0)
                <div class="flex justify-between text-emerald-600">
                    <span>Discount:</span>
                    <span class="font-semibold">-{{ $invoice->currency }} {{ number_format($invoice->discount_amount, 2) }}</span>
                </div>
            @endif
            @if($invoice->tax_amount > 0)
                <div class="flex justify-between text-slate-600">
                    <span>Tax:</span>
                    <span class="font-semibold">+{{ $invoice->currency }} {{ number_format($invoice->tax_amount, 2) }}</span>
                </div>
            @endif
            <div class="pt-2 border-t border-slate-200 flex justify-between font-bold text-slate-900">
                <span>Grand Total:</span>
                <span>{{ $invoice->currency }} {{ number_format($invoice->grand_total, 2) }}</span>
            </div>
            <div class="flex justify-between text-emerald-600 text-xs">
                <span>Amount Paid:</span>
                <span class="font-bold">-{{ $invoice->currency }} {{ number_format($invoice->amount_paid, 2) }}</span>
            </div>
            <div class="pt-2 border-t border-slate-200 flex justify-between text-lg font-black text-rose-700">
                <span>Balance Due:</span>
                <span>{{ $invoice->currency }} {{ number_format($invoice->balance_due, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Payments History Card -->
@if($payments->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
        <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center">
            <svg class="w-5 h-5 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Payment Transactions & Receipts
        </h3>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 uppercase font-semibold">
                        <th class="py-2.5 px-3">Date</th>
                        <th class="py-2.5 px-3">Receipt #</th>
                        <th class="py-2.5 px-3">Method</th>
                        <th class="py-2.5 px-3">Reference</th>
                        <th class="py-2.5 px-3 text-right">Amount</th>
                        <th class="py-2.5 px-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($payments as $p)
                        <tr>
                            <td class="py-3 px-3">{{ $p->payment_date->format('M d, Y') }}</td>
                            <td class="py-3 px-3 font-semibold text-slate-900">{{ $p->receipt_number }}</td>
                            <td class="py-3 px-3">{{ ucfirst(str_replace('_', ' ', $p->payment_method)) }}</td>
                            <td class="py-3 px-3 text-slate-500">{{ $p->transaction_reference ?? '-' }}</td>
                            <td class="py-3 px-3 text-right font-bold text-emerald-700">{{ $p->currency }} {{ number_format($p->amount, 2) }}</td>
                            <td class="py-3 px-3 text-right">
                                <a href="{{ route('portal.receipt.view', ['receiptNumber' => $p->receipt_number]) }}" target="_blank" class="text-blue-600 hover:underline font-semibold">
                                    Receipt PDF
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection

