@extends('public.layout')

@section('title', 'Platform Documentation & Developer Guide')
@section('meta_description', 'Technical documentation, REST API endpoints, module architecture, and CLI commands.')

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Developer & Admin Docs</span>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mt-2">Comprehensive Platform Documentation</h1>
            <p class="text-sm text-slate-600 mt-2">Everything required to install, configure, operate, and integrate with the platform.</p>
        </div>

        <div class="space-y-8">
            <!-- Quick Installation -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                    1. Quick Start Installation
                </h2>
                <div class="bg-slate-900 text-slate-100 rounded-xl p-4 font-mono text-xs space-y-1.5 overflow-x-auto">
                    <div><span class="text-slate-500"># 1. Install dependencies</span></div>
                    <div>composer install</div>
                    <div><span class="text-slate-500"># 2. Configure environment & app key</span></div>
                    <div>cp .env.example .env && php artisan key:generate</div>
                    <div><span class="text-slate-500"># 3. Migrate and seed demo tenant & SaaS plans</span></div>
                    <div>php artisan migrate --seed</div>
                    <div><span class="text-slate-500"># 4. Start local development server</span></div>
                    <div>php artisan serve</div>
                </div>
            </div>

            <!-- REST API Reference -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                    2. REST API v1 Reference
                </h2>
                <p class="text-xs text-slate-600 mb-4">All API requests require <code>Authorization: Bearer {token}</code> and operate within the authenticated user's tenant scope.</p>

                <div class="space-y-2 text-xs font-mono">
                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div><span class="font-bold text-emerald-600">GET</span> <span class="text-slate-800">/api/v1/quotations</span></div>
                        <span class="text-slate-400 font-sans text-[11px]">List tenant quotations</span>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div><span class="font-bold text-blue-600">POST</span> <span class="text-slate-800">/api/v1/quotations</span></div>
                        <span class="text-slate-400 font-sans text-[11px]">Create new quotation with line items</span>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div><span class="font-bold text-purple-600">POST</span> <span class="text-slate-800">/api/v1/quotations/{id}/convert-to-invoice</span></div>
                        <span class="text-slate-400 font-sans text-[11px]">1-Click transactional conversion</span>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div><span class="font-bold text-emerald-600">GET</span> <span class="text-slate-800">/api/v1/invoices</span></div>
                        <span class="text-slate-400 font-sans text-[11px]">List tenant invoices</span>
                    </div>
                    <div class="p-3 rounded-lg bg-slate-50 border border-slate-200 flex items-center justify-between">
                        <div><span class="font-bold text-emerald-600">GET</span> <span class="text-slate-800">/api/v1/customers</span></div>
                        <span class="text-slate-400 font-sans text-[11px]">List clients directory</span>
                    </div>
                </div>
            </div>

            <!-- Built-in CLI Tooling -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                    3. Built-in CLI Tooling
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="font-bold text-slate-800 font-mono">php artisan ai-hub:configure</div>
                        <div class="text-slate-500 mt-1">Configure AI provider, API keys, and active models.</div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="font-bold text-slate-800 font-mono">php artisan unicode-pdf:diagnose</div>
                        <div class="text-slate-500 mt-1">Diagnose Unicode fonts, script shaping, and PDF engines.</div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="font-bold text-slate-800 font-mono">php artisan attendance:simulate</div>
                        <div class="text-slate-500 mt-1">Simulate realistic biometric punches for testing.</div>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="font-bold text-slate-800 font-mono">php artisan slug:regenerate</div>
                        <div class="text-slate-500 mt-1">Audit and regenerate universal multilingual slugs.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
