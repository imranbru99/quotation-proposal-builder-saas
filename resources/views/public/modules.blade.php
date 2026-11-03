@extends('public.layout')

@section('title', 'All Business Modules - Module Registry Catalog')
@section('meta_description', 'Explore all business modules available in the platform: Quotations, Proposals, Invoices, CRM, Projects, Inventory, Attendance, and AI.')

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-600">Centralized Architecture</span>
            <h1 class="text-4xl font-black text-slate-900 tracking-tight mt-2">Modular Business OS Ecosystem</h1>
            <p class="text-sm text-slate-600 mt-2">Activate only the business modules you need. Every module operates with strict multi-tenant isolation and zero data loss on deactivation.</p>
        </div>

        @foreach($categories as $categoryName => $modules)
            <div class="mb-14">
                <div class="flex items-center space-x-3 mb-6 pb-3 border-b border-slate-200">
                    <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                    <h2 class="text-xl font-black text-slate-900 tracking-tight">{{ $categoryName }}</h2>
                    <span class="text-xs font-bold text-slate-400">({{ $modules->count() }} Modules)</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($modules as $mod)
                        <div class="bg-white rounded-2xl p-6 border border-slate-200 hover:shadow-md transition flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold px-2.5 py-0.5 rounded-full @if($mod->isCore) bg-blue-100 text-blue-700 @else bg-slate-100 text-slate-700 @endif">
                                        {{ $mod->isCore ? 'Core Module' : 'Optional Pack' }}
                                    </span>
                                    <span class="text-[10px] font-mono text-slate-400">v{{ $mod->version }}</span>
                                </div>
                                <h3 class="font-bold text-base text-slate-900 mb-2">{{ $mod->name }}</h3>
                                <p class="text-xs text-slate-600 leading-relaxed mb-4">{{ $mod->description }}</p>

                                @if(!empty($mod->features))
                                    <div class="space-y-1 mb-4">
                                        @foreach(array_slice($mod->features, 0, 3) as $feat)
                                            <div class="text-[11px] text-slate-500 flex items-center">
                                                <svg class="w-3.5 h-3.5 text-emerald-500 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                {{ $feat }}
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                                <a href="{{ route('public.module.detail', ['id' => $mod->id]) }}" class="text-xs font-bold text-blue-600 hover:underline">
                                    Full Specs & Workflows →
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

