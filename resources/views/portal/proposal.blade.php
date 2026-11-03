@extends('portal.layout')

@section('title', "Proposal: {$proposal->title}")
@section('header_company', $tenant->name ?? 'Proposal Presentation')

@section('header_actions')
    <a href="{{ route('portal.proposal.pdf', ['token' => $proposal->view_token]) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 shadow-sm transition">
        <svg class="w-4 h-4 mr-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
        Download PDF
    </a>
    @if(!$proposal->isSigned())
        <a href="{{ route('portal.sign.show', ['signToken' => $proposal->sign_token]) }}" class="inline-flex items-center px-5 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-sm transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            Sign Proposal
        </a>
    @endif
@endsection

@section('content')
<div class="space-y-6">
    <!-- Hero Banner -->
    <div class="bg-gradient-to-r from-slate-900 to-indigo-950 text-white rounded-2xl p-6 sm:p-10 shadow-sm relative overflow-hidden">
        <div class="relative z-10">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-500/20 text-blue-300 border border-blue-400/30 mb-3">
                Proposal #{{ $proposal->proposal_number }}
            </span>
            <h1 class="text-3xl sm:text-4xl font-extrabold tracking-tight mb-2">{{ $proposal->title }}</h1>
            <p class="text-slate-300 text-sm max-w-2xl">
                Prepared exclusively for <strong class="text-white">{{ $customer->name }}</strong> ({{ $customer->company_name ?? '' }})
                by <strong class="text-white">{{ $tenant->name ?? 'Our Team' }}</strong>.
            </p>

            <div class="mt-6 flex flex-wrap items-center gap-6 text-xs text-slate-300 border-t border-slate-700/60 pt-4">
                <div><strong>Issued:</strong> {{ $proposal->issue_date?->format('F d, Y') ?? now()->format('F d, Y') }}</div>
                @if($proposal->valid_until)
                    <div><strong>Valid Until:</strong> {{ $proposal->valid_until->format('F d, Y') }}</div>
                @endif
                <div><strong>Total Investment:</strong> <span class="text-emerald-400 font-bold text-sm">{{ $proposal->currency }} {{ number_format($proposal->total_amount, 2) }}</span></div>
                <div>
                    <strong>Status:</strong>
                    <span class="font-bold uppercase @if($proposal->isSigned()) text-emerald-400 @else text-blue-300 @endif">
                        {{ $proposal->status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Modular Sections -->
    @foreach($sections as $section)
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 mb-4 flex items-center">
                <span class="w-2.5 h-2.5 bg-blue-600 rounded-full mr-2.5"></span>
                {{ $section->title }}
            </h2>
            <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line prose prose-slate max-w-none">
                {{ $section->content }}
            </div>
        </div>
    @endforeach

    <!-- Payment Milestones -->
    @if($milestones->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100 mb-4 flex items-center">
                <span class="w-2.5 h-2.5 bg-emerald-600 rounded-full mr-2.5"></span>
                Payment & Deliverable Milestones
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 text-xs font-semibold uppercase tracking-wider">
                            <th class="py-3 px-4">Milestone</th>
                            <th class="py-3 px-4">Target Date</th>
                            <th class="py-3 px-4 text-center">Share</th>
                            <th class="py-3 px-4 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($milestones as $m)
                            <tr>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $m->title }}</div>
                                    @if($m->description)
                                        <div class="text-xs text-slate-500 mt-0.5">{{ $m->description }}</div>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-600">{{ $m->due_date?->format('M d, Y') ?? 'Upon Completion' }}</td>
                                <td class="py-3.5 px-4 text-center font-semibold text-slate-700">{{ number_format($m->percentage, 0) }}%</td>
                                <td class="py-3.5 px-4 text-right font-bold text-slate-900">{{ $proposal->currency }} {{ number_format($m->amount, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <!-- Signature Audit Box if signed -->
    @if($proposal->isSigned())
        <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 sm:p-8 shadow-sm">
            <div class="flex items-center space-x-3 text-emerald-900 font-bold text-lg mb-4">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                <span>Electronically Signed & Tamper-Evident</span>
            </div>

            @foreach($signatures as $sig)
                <div class="bg-white rounded-xl p-4 border border-emerald-200 text-xs text-slate-700 space-y-1 mb-3">
                    <div><strong>Signer:</strong> {{ $sig->signer_name }} ({{ $sig->signer_email }}) @if($sig->signer_title) — {{ $sig->signer_title }} @endif</div>
                    <div><strong>Timestamp:</strong> {{ $sig->signed_at->format('F d, Y \a\t H:i:s T') }}</div>
                    <div><strong>Origin IP:</strong> {{ $sig->ip_address }}</div>
                    <div class="font-mono text-[11px] text-slate-500 break-all"><strong>SHA-256 Checksum:</strong> {{ $sig->checksum_sha256 }}</div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Call to Sign -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 sm:p-8 text-center shadow-sm">
            <h3 class="text-xl font-bold text-slate-900 mb-2">Ready to move forward?</h3>
            <p class="text-sm text-slate-600 max-w-xl mx-auto mb-6">
                Authorize this proposal securely with a legally compliant electronic signature and initialize project kickoff.
            </p>
            <a href="{{ route('portal.sign.show', ['signToken' => $proposal->sign_token]) }}" class="inline-flex items-center px-8 py-3.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                Proceed to Sign Proposal ({{ $proposal->currency }} {{ number_format($proposal->total_amount, 2) }})
            </a>
        </div>
    @endif
</div>
@endsection

