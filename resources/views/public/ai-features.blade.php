@extends('public.layout')

@section('title', 'AI Business Assistant - Multi-Provider AI SDK')
@section('meta_description', 'Integrated with laravel-ai-hub to support OpenAI, Gemini, Claude, and DeepSeek for quotes, proposals, and automated customer communications.')

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-purple-600">Multi-Provider AI Intelligence</span>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mt-2">AI-Powered Quotations & Proposals</h1>
            <p class="text-sm text-slate-600 mt-2">
                Powered by <code>imrandevbd/laravel-ai-hub</code>. Connect OpenAI, Google Gemini, Anthropic Claude, DeepSeek, or local Ollama models with centralized tenant quotas and human-in-the-loop approvals.
            </p>
        </div>

        <!-- Capability Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 font-bold text-xl flex items-center justify-center mb-4">
                    ✨
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">AI Quotation Drafter</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Paste raw customer requirements or an email thread. The AI assistant extracts deliverables, assigns realistic unit prices, calculates estimated hours, and structures standard line items ready for review.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 font-bold text-xl flex items-center justify-center mb-4">
                    📝
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Proposal Section Generator</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Instantly draft persuasive Executive Summaries, detailed Scopes of Work, clear milestones, and protective Terms & Conditions. Content remains 100% editable by your sales team before sending.
                </p>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 font-bold text-xl flex items-center justify-center mb-4">
                    ✉️
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Smart Follow-up Generator</h3>
                <p class="text-xs text-slate-600 leading-relaxed">
                    Generate personalized, polite follow-up emails for pending proposals or overdue invoices with a single click, keeping conversations professional and accelerating cash flow.
                </p>
            </div>
        </div>

        <!-- Provider Badges -->
        <div class="bg-white rounded-2xl border border-slate-200 p-8 text-center shadow-sm">
            <h3 class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-6">Supported AI Providers Out-of-the-Box</h3>
            <div class="flex flex-wrap items-center justify-center gap-4 text-xs font-bold text-slate-700">
                <span class="px-4 py-2 rounded-xl bg-slate-100 border border-slate-200">OpenAI (GPT-4o, o1, mini)</span>
                <span class="px-4 py-2 rounded-xl bg-slate-100 border border-slate-200">Google Gemini (1.5 Pro, Flash)</span>
                <span class="px-4 py-2 rounded-xl bg-slate-100 border border-slate-200">Anthropic Claude (3.5 Sonnet)</span>
                <span class="px-4 py-2 rounded-xl bg-slate-100 border border-slate-200">DeepSeek (V3, R1)</span>
                <span class="px-4 py-2 rounded-xl bg-slate-100 border border-slate-200">Groq Fast Inference</span>
                <span class="px-4 py-2 rounded-xl bg-slate-100 border border-slate-200">Ollama Local Models</span>
            </div>
        </div>
    </div>
</div>
@endsection
