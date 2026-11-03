@extends('portal.layout')

@section('title', "Quotation #{$quotation->quotation_number}")
@section('header_company', $tenant->name ?? 'Quotation Review')

@section('header_actions')
    <a href="{{ route('portal.quote.pdf', ['token' => $quotation->view_token]) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition">
        <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
        Download PDF
    </a>
@endsection

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
    <!-- Header Banner -->
    <div class="p-6 sm:p-8 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gradient-to-r from-slate-900 to-slate-800 text-white">
        <div>
            <div class="text-xs font-semibold uppercase tracking-wider text-blue-400">Formal Quotation</div>
            <h1 class="text-2xl sm:text-3xl font-extrabold mt-1">#{{ $quotation->quotation_number }}</h1>
            <p class="text-slate-300 text-sm mt-1">{{ $quotation->title ?? 'Services & Product Estimate' }}</p>
        </div>
        <div class="sm:text-right">
            <span class="inline-flex items-center px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                @if($quotation->status === 'accepted' || $quotation->status === 'converted') bg-emerald-500/20 text-emerald-300 border border-emerald-500/40
                @elseif($quotation->status === 'rejected') bg-rose-500/20 text-rose-300 border border-rose-500/40
                @else bg-blue-500/20 text-blue-300 border border-blue-500/40 @endif">
                {{ strtoupper($quotation->status) }}
            </span>
            <div class="text-2xl font-black text-white mt-2">
                {{ $quotation->currency }} {{ number_format($quotation->grand_total, 2) }}
            </div>
        </div>
    </div>

    <!-- Parties Overview -->
    <div class="p-6 sm:p-8 grid grid-cols-1 sm:grid-cols-2 gap-8 border-b border-slate-100">
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">From</h3>
            <div class="font-bold text-slate-900 text-base">{{ $tenant->name ?? 'Service Provider' }}</div>
            <div class="text-sm text-slate-600 mt-1">{{ $tenant->company_email ?? '' }}</div>
            <div class="text-sm text-slate-600">{{ $tenant->company_phone ?? '' }}</div>
            <div class="text-sm text-slate-500 mt-1">{{ $tenant->address ?? '' }} {{ $tenant->city ?? '' }}</div>
        </div>
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Prepared For</h3>
            <div class="font-bold text-slate-900 text-base">{{ $customer->name ?? 'Valued Client' }}</div>
            @if($customer->company_name)
                <div class="text-sm font-medium text-slate-700">{{ $customer->company_name }}</div>
            @endif
            <div class="text-sm text-slate-600 mt-1">{{ $customer->email ?? '' }}</div>
            <div class="text-sm text-slate-600">{{ $customer->phone ?? '' }}</div>
            <div class="text-xs text-slate-500 mt-2">
                <strong>Issue Date:</strong> {{ $quotation->issue_date?->format('M d, Y') ?? now()->format('M d, Y') }}
                @if($quotation->expiry_date)
                    <span class="mx-2">•</span> <strong>Valid Until:</strong> {{ $quotation->expiry_date->format('M d, Y') }}
                @endif
            </div>
        </div>
    </div>

    <!-- Items Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
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
            <tbody class="divide-y divide-slate-100 text-sm">
                @foreach($items as $i => $item)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="py-4 px-6 text-slate-400 text-xs">{{ $i + 1 }}</td>
                        <td class="py-4 px-6">
                            <div class="font-bold text-slate-900">{{ $item->item_name }}</div>
                            @if($item->description)
                                <div class="text-xs text-slate-500 mt-0.5">{{ $item->description }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-4 text-center font-medium">{{ number_format($item->quantity, 2) }}</td>
                        <td class="py-4 px-4 text-right font-medium text-slate-600">{{ $quotation->currency }} {{ number_format($item->unit_price, 2) }}</td>
                        <td class="py-4 px-4 text-right text-xs text-slate-500">{{ number_format($item->tax_percentage, 1) }}%</td>
                        <td class="py-4 px-6 text-right font-bold text-slate-900">{{ $quotation->currency }} {{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Totals Summary -->
    <div class="p-6 sm:p-8 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row justify-between gap-6">
        <div class="sm:max-w-md text-xs text-slate-500 space-y-2">
            @if($quotation->payment_terms)
                <div><span class="font-semibold text-slate-700">Payment Terms:</span> {{ $quotation->payment_terms }}</div>
            @endif
            @if($quotation->delivery_terms)
                <div><span class="font-semibold text-slate-700">Delivery Terms:</span> {{ $quotation->delivery_terms }}</div>
            @endif
            @if($quotation->notes)
                <div><span class="font-semibold text-slate-700">Notes:</span> {{ $quotation->notes }}</div>
            @endif
        </div>

        <div class="w-full sm:w-72 space-y-2 text-sm">
            <div class="flex justify-between text-slate-600">
                <span>Subtotal:</span>
                <span class="font-semibold">{{ $quotation->currency }} {{ number_format($quotation->subtotal, 2) }}</span>
            </div>
            @if($quotation->discount_amount > 0)
                <div class="flex justify-between text-emerald-600">
                    <span>Discount:</span>
                    <span class="font-semibold">-{{ $quotation->currency }} {{ number_format($quotation->discount_amount, 2) }}</span>
                </div>
            @endif
            @if($quotation->tax_amount > 0)
                <div class="flex justify-between text-slate-600">
                    <span>Tax:</span>
                    <span class="font-semibold">+{{ $quotation->currency }} {{ number_format($quotation->tax_amount, 2) }}</span>
                </div>
            @endif
            <div class="pt-3 border-t border-slate-200 flex justify-between text-lg font-black text-slate-900">
                <span>Grand Total:</span>
                <span class="text-blue-600">{{ $quotation->currency }} {{ number_format($quotation->grand_total, 2) }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Acceptance & Actions Box -->
@if(!in_array($quotation->status, ['accepted', 'converted', 'rejected'], true))
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
        <h2 class="text-lg font-bold text-slate-900 mb-2">Accept this Quotation</h2>
        <p class="text-sm text-slate-600 mb-6">
            Review the terms above and submit your confirmation to accept this quotation and authorize the commencement of work.
        </p>

        <form action="{{ route('portal.quote.accept', ['token' => $quotation->view_token]) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Full Legal Name *</label>
                    <input type="text" name="signer_name" value="{{ old('signer_name', $customer->name) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 uppercase mb-1">Email Address *</label>
                    <input type="email" name="signer_email" value="{{ old('signer_email', $customer->email) }}" required class="w-full px-4 py-2 border border-slate-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="pt-2">
                <label class="flex items-start space-x-3 cursor-pointer">
                    <input type="checkbox" name="consent_confirmed" value="1" required class="mt-1 h-4 w-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500">
                    <span class="text-xs text-slate-600">
                        I confirm that I am authorized to accept this quotation on behalf of <strong>{{ $customer->name }}</strong> and agree to the payment and delivery terms stated above.
                    </span>
                </label>
            </div>

            <div class="pt-4 flex flex-col sm:flex-row items-center gap-4">
                <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-sm transition flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Accept Quotation ({{ $quotation->currency }} {{ number_format($quotation->grand_total, 2) }})
                </button>
            </div>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100">
            <details class="text-xs text-slate-500 cursor-pointer">
                <summary class="hover:text-slate-700 font-medium">Need to decline or request modifications?</summary>
                <form action="{{ route('portal.quote.reject', ['token' => $quotation->view_token]) }}" method="POST" class="mt-4 space-y-3">
                    @csrf
                    <div>
                        <label class="block font-semibold mb-1">Reason for declining / requested changes:</label>
                        <textarea name="reason" rows="2" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs" placeholder="Please let us know how we can adjust this quote to meet your requirements..."></textarea>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-slate-200 hover:bg-rose-100 hover:text-rose-700 text-slate-700 font-semibold rounded-lg transition">
                        Decline Quotation
                    </button>
                </form>
            </details>
        </div>
    </div>
@elseif($quotation->status === 'accepted' || $quotation->status === 'converted')
    <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 sm:p-8 text-center text-emerald-900">
        <svg class="w-12 h-12 text-emerald-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        <h3 class="text-xl font-bold">This quotation was accepted on {{ $quotation->accepted_at?->format('F d, Y') ?? 'record' }}.</h3>
        <p class="text-sm text-emerald-700 mt-1">Thank you! Your acceptance is on file with full audit verification.</p>
    </div>
@endif
@endsection

