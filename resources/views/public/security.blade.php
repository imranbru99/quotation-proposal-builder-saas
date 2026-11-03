@extends('public.layout')

@section('title', 'Security, Multi-Tenancy & Legal Compliance')
@section('meta_description', 'Learn about our rigorous tenant isolation, SHA-256 electronic signature integrity, and financial snapshot architecture.')

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-600">Enterprise Data Protection</span>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mt-2">Built for Trust, Privacy & Integrity</h1>
            <p class="text-sm text-slate-600 mt-2">We engineer every feature with server-side authorization, immutable audit trails, and strict tenant isolation boundaries.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <!-- Pillar 1 -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 font-bold text-xl flex items-center justify-center mb-4">
                    🛡️
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Multi-Tenant Isolation</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Every tenant-owned model implements our global <code>TenantScope</code>. Server-side queries strictly enforce <code>WHERE tenant_id = ?</code>. Cross-tenant data leakage is fundamentally blocked at the Eloquent ORM and policy layers.
                </p>
            </div>

            <!-- Pillar 2 -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 font-bold text-xl flex items-center justify-center mb-4">
                    ✍️
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">SHA-256 E-Sign Integrity</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Electronic signatures record signer identity, IP address, user-agent headers, timestamp, and a cryptographic SHA-256 hash checksum of the document content. Once signed, the document version is permanently locked from modification.
                </p>
            </div>

            <!-- Pillar 3 -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 font-bold text-xl flex items-center justify-center mb-4">
                    🔒
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Financial Snapshot Fidelity</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Converting a quotation into an invoice executes inside a database transaction with duplicate-prevention locks. Line items, taxes, discounts, and currencies are copied as immutable snapshots, ensuring historical accounting accuracy.
                </p>
            </div>
        </div>

        <!-- Compliance & Jurisdiction Statement -->
        <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-base font-bold text-slate-900 mb-3">Legal Verification & Jurisdiction Notice</h3>
            <p class="text-xs text-slate-600 leading-relaxed">
                The platform complies with standard digital contracting frameworks including the US ESIGN Act, UETA, and EU eIDAS requirements for basic electronic signatures. Note that legal enforceability depends on applicable local jurisdiction, document type, and governing laws.
            </p>
        </div>
    </div>
</div>
@endsection
