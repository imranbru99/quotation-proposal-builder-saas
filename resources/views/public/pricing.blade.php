@extends('public.layout')

@section('title', 'SaaS Pricing & Subscription Plans')
@section('meta_description', 'Predictable pricing plans for businesses of all sizes: Free, Starter, Pro, and Enterprise.')

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Transparent & Predictable</span>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mt-2">Plans Designed for Every Stage of Growth</h1>
            <p class="text-sm text-slate-600 mt-2">No hidden fees, no per-invoice penalties. Scale your team, documents, and business modules as your company grows.</p>
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
                        <h3 class="font-bold text-lg text-slate-900">{{ $plan->name }}</h3>
                        <p class="text-xs text-slate-500 mt-1 min-h-[36px]">{{ $plan->description }}</p>

                        <div class="mt-4 mb-6">
                            <span class="text-3xl font-black text-slate-900">${{ number_format($plan->price_monthly, 0) }}</span>
                            <span class="text-xs text-slate-500 font-semibold">/ month</span>
                            <div class="text-[11px] text-slate-400 mt-0.5">${{ number_format($plan->price_annual, 0) }} billed annually</div>
                        </div>

                        <ul class="space-y-2.5 text-xs text-slate-600 mb-6 border-t border-slate-100 pt-4">
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Up to <strong>{{ $plan->max_users }}</strong> Users</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Up to <strong>{{ $plan->max_customers }}</strong> Clients</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <strong>{{ $plan->max_quotations_monthly }}</strong> Quotations / mo</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <strong>{{ $plan->max_invoices_monthly }}</strong> Invoices / mo</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <strong>{{ number_format($plan->ai_token_limit_monthly) }}</strong> AI Credits</li>
                            <li class="flex items-center"><svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> <strong>{{ $plan->storage_limit_mb }} MB</strong> Storage</li>
                        </ul>
                    </div>

                    <a href="{{ route('public.demo.login', ['role' => 'owner']) }}" class="w-full py-2.5 rounded-xl text-center text-xs font-bold transition @if($plan->is_featured) bg-blue-600 hover:bg-blue-700 text-white shadow-sm @else bg-slate-100 hover:bg-slate-200 text-slate-800 @endif">
                        Launch Demo
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
