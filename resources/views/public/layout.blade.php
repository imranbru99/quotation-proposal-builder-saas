<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Quotation & Proposal Builder - Send, Sign, Convert to Invoice')</title>
    <meta name="description" content="@yield('meta_description', 'Enterprise multi-tenant SaaS for creating quotes and proposals, collecting electronic signatures, and converting accepted deals into invoices.')">
    <meta name="author" content="Imran Ahmed (me@imrandev.bd)">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased font-sans flex flex-col min-h-screen">
    <!-- Top Announcement Bar -->
    <div class="bg-gradient-to-r from-blue-700 via-indigo-700 to-purple-800 text-white text-xs py-2 px-4 text-center font-medium shadow-xs">
        <span>🚀 Built with Laravel 12 & Filament 5 • Fully Modular Multi-Tenant Business OS</span>
        <a href="#demo-section" class="ml-2 underline font-bold hover:text-blue-100">Try Interactive Demo →</a>
    </div>

    <!-- Navigation Bar -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="{{ route('public.home') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-xl bg-blue-600 group-hover:bg-blue-700 transition flex items-center justify-center text-white font-black text-xl shadow-md shadow-blue-500/20">
                    Q
                </div>
                <div>
                    <span class="text-lg font-black tracking-tight text-slate-900 block leading-tight">Quotation & Proposal</span>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-600 block">Send • Sign • Invoice</span>
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <nav class="hidden lg:flex items-center space-x-7 text-sm font-semibold text-slate-600">
                <a href="{{ route('public.modules') }}" class="hover:text-blue-600 transition">Modules</a>
                <a href="{{ route('public.solutions') }}" class="hover:text-blue-600 transition">Solutions</a>
                <a href="{{ route('public.ai') }}" class="hover:text-blue-600 transition flex items-center">
                    AI Assistant
                    <span class="ml-1.5 px-1.5 py-0.5 rounded text-[10px] font-bold bg-purple-100 text-purple-700">AI</span>
                </a>
                <a href="{{ route('public.pricing') }}" class="hover:text-blue-600 transition">Pricing</a>
                <a href="{{ route('public.security') }}" class="hover:text-blue-600 transition">Security</a>
                <a href="{{ route('public.docs') }}" class="hover:text-blue-600 transition">Docs</a>
                <a href="{{ route('public.contact') }}" class="hover:text-blue-600 transition">Contact</a>
            </nav>

            <!-- Actions -->
            <div class="flex items-center space-x-3">
                <div class="relative group">
                    <button class="inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-bold rounded-xl text-slate-700 bg-white hover:bg-slate-50 transition shadow-xs">
                        <span>Demo Roles</span>
                        <svg class="w-4 h-4 ml-1.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                    <div class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-slate-200 py-2 hidden group-hover:block transition z-50">
                        <div class="px-4 py-1.5 text-[10px] font-bold text-slate-400 uppercase tracking-wider">1-Click Fast Login</div>
                        <a href="{{ route('public.demo.login', ['role' => 'owner']) }}" class="block px-4 py-2 text-xs font-semibold text-slate-800 hover:bg-blue-50 hover:text-blue-600">👑 Business Owner (/app)</a>
                        <a href="{{ route('public.demo.login', ['role' => 'sales']) }}" class="block px-4 py-2 text-xs font-semibold text-slate-800 hover:bg-blue-50 hover:text-blue-600">💼 Sales Executive (/app)</a>
                        <a href="{{ route('public.demo.login', ['role' => 'accountant']) }}" class="block px-4 py-2 text-xs font-semibold text-slate-800 hover:bg-blue-50 hover:text-blue-600">📊 Finance & Accountant (/app)</a>
                        <div class="border-t border-slate-100 my-1"></div>
                        <a href="{{ route('public.demo.login', ['role' => 'superadmin']) }}" class="block px-4 py-2 text-xs font-semibold text-purple-700 hover:bg-purple-50">⚡ SuperAdmin (/platform)</a>
                    </div>
                </div>

                <a href="/app" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold shadow-md shadow-blue-500/20 transition">
                    Open Dashboard →
                </a>
            </div>
        </div>
    </header>

    <!-- Content Block -->
    <main class="flex-grow">
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-medium">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('info'))
            <div class="max-w-7xl mx-auto px-4 mt-6">
                <div class="p-4 rounded-xl bg-blue-50 border border-blue-200 text-blue-800 text-sm font-medium">
                    {{ session('info') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 border-t border-slate-800 pt-16 pb-12 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8 mb-12">
                <div class="col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center text-white font-black text-lg">
                            Q
                        </div>
                        <span class="text-lg font-black text-white">Quotation & Proposal SaaS</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed max-w-sm mb-4">
                        Enterprise multi-tenant business OS. Empowering businesses to create stunning quotations and proposals, collect tamper-proof electronic signatures, and convert accepted deals into invoices with zero manual reconciliation.
                    </p>
                    <div class="text-xs text-slate-500">
                        Designed & Architected by <a href="https://imrandev.bd" target="_blank" class="text-blue-400 hover:underline font-bold">Imran Ahmed</a> (<a href="mailto:me@imrandev.bd" class="hover:underline">me@imrandev.bd</a>)
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Core Modules</h4>
                    <ul class="text-xs space-y-2.5">
                        <li><a href="{{ route('public.module.detail', ['id' => 'quotations']) }}" class="hover:text-white transition">Quotation Builder</a></li>
                        <li><a href="{{ route('public.module.detail', ['id' => 'proposals']) }}" class="hover:text-white transition">Proposal Builder</a></li>
                        <li><a href="{{ route('public.module.detail', ['id' => 'esignature']) }}" class="hover:text-white transition">Electronic Signatures</a></li>
                        <li><a href="{{ route('public.module.detail', ['id' => 'invoices']) }}" class="hover:text-white transition">Invoice Management</a></li>
                        <li><a href="{{ route('public.module.detail', ['id' => 'customers']) }}" class="hover:text-white transition">Customer Directory</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Optional Packs</h4>
                    <ul class="text-xs space-y-2.5">
                        <li><a href="{{ route('public.module.detail', ['id' => 'crm']) }}" class="hover:text-white transition">CRM & Pipelines</a></li>
                        <li><a href="{{ route('public.module.detail', ['id' => 'inventory']) }}" class="hover:text-white transition">Inventory Tracking</a></li>
                        <li><a href="{{ route('public.module.detail', ['id' => 'projects']) }}" class="hover:text-white transition">Projects & Tasks</a></li>
                        <li><a href="{{ route('public.module.detail', ['id' => 'attendance']) }}" class="hover:text-white transition">Attendance Hub</a></li>
                        <li><a href="{{ route('public.module.detail', ['id' => 'ai_assistant']) }}" class="hover:text-white transition">AI Business Assistant</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider mb-4">Platform & Trust</h4>
                    <ul class="text-xs space-y-2.5">
                        <li><a href="{{ route('public.security') }}" class="hover:text-white transition">Security & Multi-Tenancy</a></li>
                        <li><a href="{{ route('public.pricing') }}" class="hover:text-white transition">Pricing Plans</a></li>
                        <li><a href="{{ route('public.docs') }}" class="hover:text-white transition">API Documentation</a></li>
                        <li><a href="{{ route('public.solutions') }}" class="hover:text-white transition">Industry Solutions</a></li>
                        <li><a href="{{ route('public.contact') }}" class="hover:text-white transition">Enterprise Support</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-slate-800 pt-8 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-500">
                <div>
                    © 2026 Quotation and Proposal Builder. Licensed under MIT. Official website: <a href="https://imrandev.bd" target="_blank" class="text-blue-400 hover:underline">https://imrandev.bd</a>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-6">
                    <span>PHP 8.5</span>
                    <span>Laravel 12.x</span>
                    <span>Filament 5.x</span>
                    <span>Unicode PDF</span>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
