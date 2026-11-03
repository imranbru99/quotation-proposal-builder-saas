<x-filament-panels::page>
    <div class="mb-6 p-4 rounded-xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900 text-blue-900 dark:text-blue-200 text-xs">
        <div class="font-bold text-sm mb-1">Zero Data Loss Guarantee</div>
        <p>
            You can freely activate or deactivate optional modules to adapt the application to your business needs. Deactivating a module safely hides its interface, navigation menus, and routes. <strong>All past transactions, quotes, records, and audits remain permanently intact in your database.</strong>
        </p>
    </div>

    <div class="space-y-8">
        @foreach($categories as $categoryName => $modules)
            <div>
                <div class="flex items-center space-x-2 mb-4 pb-2 border-b border-gray-200 dark:border-gray-800">
                    <div class="w-2.5 h-2.5 rounded-full bg-primary-600"></div>
                    <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ $categoryName }}</h3>
                    <span class="text-xs text-gray-500">({{ $modules->count() }} modules)</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($modules as $mod)
                        @php
                            $isActive = in_array($mod->id, $activeIds, true);
                        @endphp
                        <div class="p-5 rounded-xl border bg-white dark:bg-gray-900 {{ $isActive ? 'border-primary-500 ring-1 ring-primary-500/20' : 'border-gray-200 dark:border-gray-800' }} flex flex-col justify-between transition">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-full {{ $mod->isCore ? 'bg-primary-100 text-primary-800 dark:bg-primary-950 dark:text-primary-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                                        {{ $mod->isCore ? 'Core System' : 'Optional Module' }}
                                    </span>

                                    @if($isActive)
                                        <span class="inline-flex items-center text-xs font-bold text-emerald-600 dark:text-emerald-400">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 mr-1.5"></span> Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-xs font-medium text-gray-400">
                                            <span class="w-2 h-2 rounded-full bg-gray-400 mr-1.5"></span> Disabled
                                        </span>
                                    @endif
                                </div>

                                <h4 class="font-bold text-sm text-gray-900 dark:text-white mb-1.5">{{ $mod->name }}</h4>
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed mb-4">
                                    {{ $mod->description }}
                                </p>

                                @if(!empty($mod->dependencies))
                                    <div class="text-[11px] text-gray-400 mb-4">
                                        <span class="font-semibold text-gray-600 dark:text-gray-300">Requires:</span> {{ implode(', ', $mod->dependencies) }}
                                    </div>
                                @endif
                            </div>

                            <div class="pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                                <span class="text-[10px] font-mono text-gray-400">ID: {{ $mod->id }}</span>

                                @if($mod->isCore)
                                    <span class="text-xs font-semibold text-gray-400 cursor-not-allowed" title="Core foundation modules are permanent">
                                        Essential Core
                                    </span>
                                @else
                                    <button
                                        wire:click="toggleModule('{{ $mod->id }}')"
                                        type="button"
                                        class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition {{ $isActive ? 'bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 dark:bg-rose-950 dark:text-rose-300 dark:border-rose-900' : 'bg-primary-600 text-white hover:bg-primary-700 shadow-sm' }}"
                                    >
                                        {{ $isActive ? 'Deactivate Module' : 'Activate Module' }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>

