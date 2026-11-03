<?php

namespace App\Services\Modules;

use App\Models\Tenant;
use App\Models\TenantModule;
use App\Services\Tenancy\TenantContext;

class ModuleManager
{
    public function __construct(
        protected ModuleRegistry $registry,
        protected TenantContext $context
    ) {}

    public function getRegistry(): ModuleRegistry
    {
        return $this->registry;
    }

    /**
     * Check if a module is active for the given or current tenant.
     * Core modules are always active by default unless explicitly disabled.
     */
    public function isModuleActive(string $moduleId, ?Tenant $tenant = null): bool
    {
        $tenant = $tenant ?? $this->context->getTenant();
        if (!$tenant) {
            // In public or system context, check registry existence
            return $this->registry->exists($moduleId);
        }

        $module = $this->registry->find($moduleId);
        if (!$module) {
            return false;
        }

        // Query the tenant_modules record
        $record = TenantModule::where('tenant_id', $tenant->id)
            ->where('module_id', $moduleId)
            ->first();

        if ($record !== null) {
            return (bool) $record->is_active;
        }

        // If no explicit record yet, core modules default to true; optional default to false
        return $module->isCore;
    }

    /**
     * Get list of active module IDs for a tenant.
     *
     * @return array<string>
     */
    public function getActiveModuleIds(?Tenant $tenant = null): array
    {
        $tenant = $tenant ?? $this->context->getTenant();
        if (!$tenant) {
            return $this->registry->all()->pluck('id')->toArray();
        }

        $records = TenantModule::where('tenant_id', $tenant->id)->get()->keyBy('module_id');
        $active = [];

        foreach ($this->registry->all() as $id => $module) {
            if ($records->has($id)) {
                if ($records[$id]->is_active) {
                    $active[] = $id;
                }
            } elseif ($module->isCore) {
                $active[] = $id;
            }
        }

        return $active;
    }

    /**
     * Activate a module for a tenant with dependency checks.
     *
     * @return array{success: bool, message: string}
     */
    public function activate(string $moduleId, Tenant $tenant): array
    {
        $module = $this->registry->find($moduleId);
        if (!$module) {
            return ['success' => false, 'message' => "Module '{$moduleId}' does not exist."];
        }

        $activeIds = $this->getActiveModuleIds($tenant);
        $missing = $this->registry->getMissingDependencies($moduleId, $activeIds);

        if (!empty($missing)) {
            $deps = implode(', ', $missing);
            return [
                'success' => false,
                'message' => "Cannot activate '{$module->name}'. Missing required dependencies: {$deps}.",
            ];
        }

        TenantModule::updateOrCreate(
            ['tenant_id' => $tenant->id, 'module_id' => $moduleId],
            [
                'is_active' => true,
                'activated_at' => now(),
            ]
        );

        return ['success' => true, 'message' => "Module '{$module->name}' activated successfully."];
    }

    /**
     * Deactivate a module for a tenant.
     * IMPORTANT: Never deletes any historical database records!
     *
     * @return array{success: bool, message: string}
     */
    public function deactivate(string $moduleId, Tenant $tenant): array
    {
        $module = $this->registry->find($moduleId);
        if (!$module) {
            return ['success' => false, 'message' => "Module '{$moduleId}' does not exist."];
        }

        $activeIds = $this->getActiveModuleIds($tenant);
        $check = $this->registry->canDeactivate($moduleId, $activeIds);

        if (!$check['can_deactivate']) {
            $dependents = implode(', ', $check['dependents']);
            return [
                'success' => false,
                'message' => "Cannot deactivate '{$module->name}' because the following active modules depend on it: {$dependents}.",
            ];
        }

        TenantModule::updateOrCreate(
            ['tenant_id' => $tenant->id, 'module_id' => $moduleId],
            [
                'is_active' => false,
                'deactivated_at' => now(),
            ]
        );

        return [
            'success' => true,
            'message' => "Module '{$module->name}' deactivated. Historical data has been preserved.",
        ];
    }

    /**
     * Initialize default active modules for a newly registered tenant.
     */
    public function syncDefaultModules(Tenant $tenant, ?array $moduleIds = null): void
    {
        $idsToActivate = $moduleIds ?? $this->registry->all()->where('isCore', true)->pluck('id')->toArray();

        foreach ($idsToActivate as $id) {
            TenantModule::updateOrCreate(
                ['tenant_id' => $tenant->id, 'module_id' => $id],
                [
                    'is_active' => true,
                    'activated_at' => now(),
                ]
            );
        }
    }
}
