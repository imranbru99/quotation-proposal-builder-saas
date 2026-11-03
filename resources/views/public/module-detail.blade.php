@extends('public.layout')

@section('title', "{$module->name} - Module Specifications")
@section('meta_description', $module->description)

@section('content')
<div class="py-16 bg-slate-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="text-xs font-semibold text-slate-500 mb-6 flex items-center space-x-2">
            <a href="{{ route('public.home') }}" class="hover:underline">Home</a>
            <span>/</span>
            <a href="{{ route('public.modules') }}" class="hover:underline">Modules</a>
            <span>/</span>
            <span class="text-slate-900">{{ $module->name }}</span>
        </nav>

        <!-- Module Header Card -->
        <div class="bg-white rounded-2xl border border-slate-200 p-8 shadow-sm mb-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-100 pb-6 mb-6">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <span class="text-xs font-bold px-3 py-1 rounded-full @if($module->isCore) bg-blue-100 text-blue-700 @else bg-slate-100 text-slate-700 @endif">
                            {{ $module->category }}
                        </span>
                        <span class="text-xs text-slate-400 font-mono">Module ID: {{ $module->id }} • v{{ $module->version }}</span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">{{ $module->name }}</h1>
                </div>

                <div class="flex items-center space-x-3">
                    <a href="{{ route('public.demo.login', ['role' => 'owner']) }}" class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                        Test in Live Demo →
                    </a>
                </div>
            </div>

            <p class="text-base text-slate-700 leading-relaxed max-w-3xl">
                {{ $module->description }}
            </p>
        </div>

        <!-- In-Depth Sections -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-8">
                <!-- Features Card -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                        Capabilities & Supported Workflows
                    </h2>
                    <ul class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs text-slate-700">
                        @foreach($module->features as $feat)
                            <li class="flex items-center p-3 rounded-xl bg-slate-50 border border-slate-100">
                                <svg class="w-4 h-4 text-emerald-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span class="font-medium">{{ $feat }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- How It Operates -->
                <div class="bg-white rounded-2xl border border-slate-200 p-6 sm:p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100">
                        Zero Data Deletion Guarantee
                    </h2>
                    <p class="text-xs text-slate-600 leading-relaxed">
                        When this module is deactivated in your tenant settings, the application hides navigation links, prevents access to its web and API routes, and stops background scheduled workers. <strong>All existing database tables, historical records, and audits remain 100% preserved.</strong> Reactivating the module instantly restores complete operational access.
                    </p>
                </div>
            </div>

            <!-- Sidebar Specs -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Module Architecture</h3>
                    <div class="space-y-3 text-xs">
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">Classification:</span>
                            <span class="font-bold text-slate-900">{{ $module->isCore ? 'Core System' : 'Optional Pack' }}</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">Tenant Isolated:</span>
                            <span class="font-bold text-emerald-600">Yes (TenantScope)</span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">Dependencies:</span>
                            <span class="font-bold text-slate-900">
                                @if(empty($module->dependencies))
                                    None (Standalone)
                                @else
                                    {{ implode(', ', $module->dependencies) }}
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between py-1 border-b border-slate-100">
                            <span class="text-slate-500">REST API Ready:</span>
                            <span class="font-bold text-emerald-600">Yes (/api/v1)</span>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 rounded-2xl border border-blue-200 p-6 text-center">
                    <h4 class="font-bold text-slate-900 text-sm mb-1">Explore This Module Live</h4>
                    <p class="text-xs text-slate-600 mb-4">Log into the demo environment and toggle this module inside the Business Panel.</p>
                    <a href="{{ route('public.demo.login', ['role' => 'owner']) }}" class="block w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs shadow-sm transition">
                        Launch Demo as Owner
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
