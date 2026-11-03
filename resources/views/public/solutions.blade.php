@extends('public.layout')

@section('title', 'Industry Solutions - Tailored Business Architectures')
@section('meta_description', 'Discover how our modular SaaS adapts to Freelancers, Digital Agencies, Construction Contractors, and Trading Companies.')

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Tailored Workflows</span>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mt-2">One Platform. Every Industry.</h1>
            <p class="text-sm text-slate-600 mt-2">Instead of buying separate disconnected tools, configure our modular ecosystem to match your exact business model.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-16">
            <!-- Solution 1: Freelancers & Solo Consultants -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 font-bold text-xl flex items-center justify-center mb-4">
                    🎯
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Freelancers & Solo Consultants</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                    Keep your operations fast, lean, and professional. Send branded quotations and rich proposals, collect legally binding electronic signatures, and convert accepted deals directly into invoices.
                </p>
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-xs">
                    <div class="font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-2">Recommended Active Modules</div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Quotations</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Proposals</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">E-Signatures</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Invoices</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Unicode PDF</span>
                    </div>
                </div>
            </div>

            <!-- Solution 2: Digital & Creative Agencies -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 font-bold text-xl flex items-center justify-center mb-4">
                    🚀
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Digital & Creative Agencies</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                    Close complex client engagements with modular proposals, payment milestones, project delivery tracking, and lead CRM pipelines.
                </p>
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-xs">
                    <div class="font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-2">Recommended Active Modules</div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">CRM & Leads</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">AI Drafter</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Milestone Billing</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Projects & Tasks</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Client Portal</span>
                    </div>
                </div>
            </div>

            <!-- Solution 3: Construction & Contractors -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-600 font-bold text-xl flex items-center justify-center mb-4">
                    🏗️
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">General Contractors & Construction</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                    Manage comprehensive field estimates, procurement purchase orders, employee biometric attendance, and on-site material inventories.
                </p>
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-xs">
                    <div class="font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-2">Recommended Active Modules</div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Detailed Estimates</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Purchase Orders</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Inventory</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Attendance Hub</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Expenses</span>
                    </div>
                </div>
            </div>

            <!-- Solution 4: Trading & Wholesale -->
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 font-bold text-xl flex items-center justify-center mb-4">
                    📦
                </div>
                <h3 class="text-xl font-black text-slate-900 mb-2">Trading & Wholesale Distribution</h3>
                <p class="text-xs text-slate-600 leading-relaxed mb-6">
                    Connect multi-warehouse stock levels with fast quotation generation, custom price lists, volume discounts, and supplier procurement.
                </p>
                <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 text-xs">
                    <div class="font-bold text-slate-700 uppercase tracking-wider text-[10px] mb-2">Recommended Active Modules</div>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Product Catalogs</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Warehousing</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Suppliers</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Invoices</span>
                        <span class="px-2.5 py-1 bg-white border border-slate-200 rounded-md font-semibold text-slate-700">Payments</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

