@extends('public.layout')

@section('title', 'Quotation and Proposal Builder: Send, Sign, Convert to Invoice')
@section('meta_description', 'Enterprise multi-tenant business SaaS built with Laravel 12 & Filament 5. Create quotes, collect legal signatures, and convert to invoices with full transactional integrity.')

@section('content')
<!-- 1. Hero Section -->
<section class="relative pt-16 pb-20 overflow-hidden bg-gradient-to-b from-blue-50/50 via-white to-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <!-- Badge -->
        <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-blue-100 border border-blue-200 text-blue-700 text-xs font-bold tracking-wide uppercase mb-6 shadow-xs">
            <span class="w-2 h-2 rounded-full bg-blue-600 mr-2 animate-pulse"></span>
            Enterprise Multi-Tenant SaaS • Laravel 12 & Filament 5
        </div>

        <!-- Headline -->
        <h1 class="text-4xl sm:text-6xl lg:text-7xl font-black text-slate-900 tracking-tight leading-tight max-w-5xl mx-auto">
            Quotation & Proposal Builder
            <span class="block bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 bg-clip-text text-transparent">
                Send, Sign, Convert to Invoice
            </span>
        </h1>

        <!-- Subheadline -->
        <p class="mt-6 text-lg sm:text-xl text-slate-600 max-w-3xl mx-auto leading-relaxed">
            One adaptable business management operating system. Create interactive quotations and proposals, collect legally tamper-evident electronic signatures, and convert accepted deals into invoices with 100% financial snapshot fidelity.
        </p>

        <!-- CTA Buttons -->
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="#demo-section" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-base shadow-lg shadow-blue-500/25 transition flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Launch Interactive Demo
            </a>
            <a href="{{ route('public.modules') }}" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-white hover:bg-slate-50 border border-slate-300 text-slate-700 font-bold text-base shadow-sm transition flex items-center justify-center">
                Explore All {{ $modules->count() }} Modules →
            </a>
        </div>

        <!-- Quick feature badges -->
        <div class="mt-12 flex flex-wrap items-center justify-center gap-6 text-xs font-semibold text-slate-500">
            <span class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Zero Data Loss on Module Toggle</span>
            <span class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> SHA-256 E-Signature Audit Trail</span>
            <span class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Multilingual Unicode PDF (Arabic/Bangla/EN)</span>
            <span class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Multi-Tenant Isolation Scopes</span>
        </div>
    </div>
</section>

<!-- 2. Transactional Workflow Visualizer -->
<section class="py-16 bg-white border-y border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <h2 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-2">The Closed-Loop Deal Cycle</h2>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">From Client Discovery to Paid Invoices</h3>
            <p class="text-sm text-slate-600 mt-2">Every step is connected through database transactions, immutable financial snapshots, and audit trails.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 relative">
            <!-- Step 1 -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 hover:border-blue-500 hover:shadow-md transition">
                <div class="w-9 h-9 rounded-xl bg-blue-600 text-white font-bold flex items-center justify-center text-sm mb-3">1</div>
                <h4 class="font-bold text-slate-900 text-base">Quotation</h4>
                <p class="text-xs text-slate-600 mt-1">Build estimates with custom items, volume discounts, taxes, and validity dates.</p>
                <div class="mt-3 text-[11px] font-semibold text-blue-600">PDF & Public Link</div>
            </div>

            <!-- Step 2 -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 hover:border-indigo-500 hover:shadow-md transition">
                <div class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-bold flex items-center justify-center text-sm mb-3">2</div>
                <h4 class="font-bold text-slate-900 text-base">Proposal</h4>
                <p class="text-xs text-slate-600 mt-1">Structure scope of work, deliverables, payment milestones, and terms.</p>
                <div class="mt-3 text-[11px] font-semibold text-indigo-600">AI-Powered Drafter</div>
            </div>

            <!-- Step 3 -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 hover:border-purple-500 hover:shadow-md transition">
                <div class="w-9 h-9 rounded-xl bg-purple-600 text-white font-bold flex items-center justify-center text-sm mb-3">3</div>
                <h4 class="font-bold text-slate-900 text-base">E-Signature</h4>
                <p class="text-xs text-slate-600 mt-1">Collect legal handwritten or typed consent with IP, UA, and SHA-256 locking.</p>
                <div class="mt-3 text-[11px] font-semibold text-purple-600">Tamper-Proof Audit</div>
            </div>

            <!-- Step 4 -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 hover:border-emerald-500 hover:shadow-md transition">
                <div class="w-9 h-9 rounded-xl bg-emerald-600 text-white font-bold flex items-center justify-center text-sm mb-3">4</div>
                <h4 class="font-bold text-slate-900 text-base">1-Click Invoice</h4>
                <p class="text-xs text-slate-600 mt-1">Instantly convert accepted quotes into invoices with duplicate prevention.</p>
                <div class="mt-3 text-[11px] font-semibold text-emerald-600">Financial Snapshot</div>
            </div>

            <!-- Step 5 -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 hover:border-amber-500 hover:shadow-md transition">
                <div class="w-9 h-9 rounded-xl bg-amber-600 text-white font-bold flex items-center justify-center text-sm mb-3">5</div>
                <h4 class="font-bold text-slate-900 text-base">Payment & Receipt</h4>
                <p class="text-xs text-slate-600 mt-1">Record partial or full payments, track balances, and issue verified receipts.</p>
                <div class="mt-3 text-[11px] font-semibold text-amber-600">Instant Receipts</div>
            </div>
        </div>
    </div>
</section>

<!-- 3. Dynamic Module Marketplace Preview -->
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12">
            <div>
                <h2 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-2">Centralized Module Registry</h2>
                <h3 class="text-3xl font-black text-slate-900 tracking-tight">Activate What You Need. Never Delete Data.</h3>
                <p class="text-sm text-slate-600 mt-1">Whether you are a solo freelancer or a large contractor, toggle business packs with 1-click.</p>
            </div>
            <a href="{{ route('public.modules') }}" class="mt-4 md:mt-0 text-sm font-bold text-blue-600 hover:underline">
                Browse Full Catalog ({{ $modules->count() }} Modules) →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($modules->take(8) as $mod)
                <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-lg transition flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full @if($mod->isCore) bg-blue-100 text-blue-700 @else bg-slate-100 text-slate-700 @endif">
                                {{ $mod->category }}
                            </span>
                            @if($mod->isCore)
                                <span class="text-[10px] uppercase font-bold text-emerald-600 flex items-center">Core</span>
                            @else
                                <span class="text-[10px] uppercase font-bold text-slate-400">Optional</span>
                            @endif
                        </div>
                        <h4 class="font-bold text-slate-900 text-base mb-2">{{ $mod->name }}</h4>
                        <p class="text-xs text-slate-600 leading-relaxed mb-4 line-clamp-3">{{ $mod->description }}</p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                        <a href="{{ route('public.module.detail', ['id' => $mod->id]) }}" class="text-xs font-bold text-blue-600 hover:underline">
                            View Module Specs →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 4. Interactive Demo Sandbox Section -->
<section id="demo-section" class="py-20 bg-slate-900 text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="px-3.5 py-1.5 rounded-full bg-blue-500/20 text-blue-300 text-xs font-bold tracking-wider uppercase border border-blue-400/30">
                100% Functional Demo Sandbox
            </span>
            <h2 class="text-3xl sm:text-4xl font-black mt-4">Experience the Enterprise Dashboard Live</h2>
            <p class="text-slate-300 text-sm mt-2">
                Click any role below to be logged in immediately. Test quotation workflows, proposal signing, 1-click invoice conversions, and module toggles.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto">
            <!-- Business Owner -->
            <a href="{{ route('public.demo.login', ['role' => 'owner']) }}" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700 hover:border-blue-500 rounded-2xl p-6 text-left transition group">
                <div class="w-12 h-12 rounded-xl bg-blue-600/30 text-blue-400 flex items-center justify-center font-bold text-xl mb-4 group-hover:scale-110 transition">
                    👑
                </div>
                <h4 class="font-bold text-white text-base">Business Owner</h4>
                <p class="text-xs text-slate-400 mt-1">Full control over tenant modules, financial reports, users, quotes, and settings.</p>
                <div class="mt-4 text-xs font-bold text-blue-400 flex items-center">
                    Login as Owner →
                </div>
            </a>

            <!-- Sales Executive -->
            <a href="{{ route('public.demo.login', ['role' => 'sales']) }}" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700 hover:border-indigo-500 rounded-2xl p-6 text-left transition group">
                <div class="w-12 h-12 rounded-xl bg-indigo-600/30 text-indigo-400 flex items-center justify-center font-bold text-xl mb-4 group-hover:scale-110 transition">
                    💼
                </div>
                <h4 class="font-bold text-white text-base">Sales Executive</h4>
                <p class="text-xs text-slate-400 mt-1">Draft quotations, build AI proposals, dispatch client review links, and track deals.</p>
                <div class="mt-4 text-xs font-bold text-indigo-400 flex items-center">
                    Login as Sales →
                </div>
            </a>

            <!-- Accountant -->
            <a href="{{ route('public.demo.login', ['role' => 'accountant']) }}" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700 hover:border-emerald-500 rounded-2xl p-6 text-left transition group">
                <div class="w-12 h-12 rounded-xl bg-emerald-600/30 text-emerald-400 flex items-center justify-center font-bold text-xl mb-4 group-hover:scale-110 transition">
                    📊
                </div>
                <h4 class="font-bold text-white text-base">Finance / Accountant</h4>
                <p class="text-xs text-slate-400 mt-1">Manage invoice conversions, record payments, calculate taxes, and issue receipts.</p>
                <div class="mt-4 text-xs font-bold text-emerald-400 flex items-center">
                    Login as Finance →
                </div>
            </a>

            <!-- SuperAdmin -->
            <a href="{{ route('public.demo.login', ['role' => 'superadmin']) }}" class="bg-slate-800/80 hover:bg-slate-800 border border-slate-700 hover:border-purple-500 rounded-2xl p-6 text-left transition group">
                <div class="w-12 h-12 rounded-xl bg-purple-600/30 text-purple-400 flex items-center justify-center font-bold text-xl mb-4 group-hover:scale-110 transition">
                    ⚡
                </div>
                <h4 class="font-bold text-white text-base">Super Administrator</h4>
                <p class="text-xs text-slate-400 mt-1">Global platform administration: manage all tenants, SaaS tiers, AI providers, and system health.</p>
                <div class="mt-4 text-xs font-bold text-purple-400 flex items-center">
                    Open Platform Admin →
                </div>
            </a>
        </div>
    </div>
</section>

<!-- 5. Transparent SaaS Pricing Preview -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-2">Predictable SaaS Plans</h2>
            <h3 class="text-3xl sm:text-4xl font-black text-slate-900 tracking-tight">Choose the Plan That Matches Your Growth</h3>
            <p class="text-sm text-slate-600 mt-2">All plans include multi-tenant isolation, quotation builder, e-signatures, and Unicode PDF generation.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach($plans as $plan)
                <div class="rounded-2xl p-6 border @if($plan->is_featured) border-blue-600 shadow-xl ring-2 ring-blue-600/20 @else border-slate-200 shadow-sm @endif flex flex-col justify-between bg-white relative">
                    @if($plan->is_featured)
                        <span class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white text-[10px] font-black uppercase px-3 py-0.5 rounded-full tracking-wide">
                            Most Popular
                        </span>
                    @endif

                    <div>
                        <h4 class="font-bold text-lg text-slate-900">{{ $plan->name }}</h4>
                        <p class="text-xs text-slate-500 mt-1 min-h-[36px]">{{ $plan->description }}</p>

                        <div class="mt-4 mb-6">
                            <span class="text-3xl font-black text-slate-900">${{ number_format($plan->price_monthly, 0) }}</span>
                            <span class="text-xs text-slate-500 font-semibold">/ month</span>
                        </div>

                        <ul class="space-y-2.5 text-xs text-slate-600 mb-6">
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Up to <strong>{{ $plan->max_users }}</strong> Users</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Up to <strong>{{ $plan->max_customers }}</strong> Clients</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <strong>{{ $plan->max_quotations_monthly }}</strong> Quotes / month</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <strong>{{ $plan->max_invoices_monthly }}</strong> Invoices / month</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <strong>{{ number_format($plan->ai_token_limit_monthly) }}</strong> AI Credits</li>
                        </ul>
                    </div>

                    <a href="{{ route('public.demo.login', ['role' => 'owner']) }}" class="w-full py-2.5 rounded-xl text-center text-xs font-bold transition @if($plan->is_featured) bg-blue-600 hover:bg-blue-700 text-white shadow-sm @else bg-slate-100 hover:bg-slate-200 text-slate-800 @endif">
                        Get Started
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- 6. FAQ Section -->
<section class="py-20 bg-slate-50 border-t border-slate-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-2">Frequently Asked Questions</h2>
            <h3 class="text-3xl font-black text-slate-900 tracking-tight">Everything You Need to Know</h3>
        </div>

        <div class="space-y-4">
            <details class="bg-white rounded-xl border border-slate-200 p-5 cursor-pointer shadow-xs">
                <summary class="font-bold text-slate-900 text-sm">How does quotation to invoice conversion work?</summary>
                <div class="mt-3 text-xs text-slate-600 leading-relaxed">
                    Once a quotation is accepted by your client (or manually approved by your sales executive), a 1-click action triggers our transactional converter. It captures an immutable snapshot of all line items, taxes, discounts, and currency, sets the quote to 'converted', and creates the invoice with zero duplicate risk.
                </div>
            </details>

            <details class="bg-white rounded-xl border border-slate-200 p-5 cursor-pointer shadow-xs">
                <summary class="font-bold text-slate-900 text-sm">What happens to our data if we deactivate a module?</summary>
                <div class="mt-3 text-xs text-slate-600 leading-relaxed">
                    Our architecture guarantees <strong>zero data deletion</strong>. Deactivating an optional module (e.g. Inventory or Attendance) merely hides the UI views, navigation links, and guards routes. Existing database tables and historical transaction records remain completely untouched and restore immediately upon reactivation.
                </div>
            </details>

            <details class="bg-white rounded-xl border border-slate-200 p-5 cursor-pointer shadow-xs">
                <summary class="font-bold text-slate-900 text-sm">Are the electronic signatures legally verifiable?</summary>
                <div class="mt-3 text-xs text-slate-600 leading-relaxed">
                    Yes. Each electronic signature records the signer's full legal name, verified email, IP address, user-agent string, explicit legal intent confirmation, and a cryptographic SHA-256 hash checksum computed over the document contents at the moment of execution.
                </div>
            </details>

            <details class="bg-white rounded-xl border border-slate-200 p-5 cursor-pointer shadow-xs">
                <summary class="font-bold text-slate-900 text-sm">Does the PDF generator support non-Latin languages?</summary>
                <div class="mt-3 text-xs text-slate-600 leading-relaxed">
                    Yes. Integrated with <code>laravel-unicode-pdf</code>, the platform supports complex script shaping and font embedding for Bengali (বাংলা), Arabic RTL (العربية), and international Latin alphabets.
                </div>
            </details>
        </div>
    </div>
</section>
@endsection
