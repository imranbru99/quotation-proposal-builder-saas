<?php

use App\Models\Tenant;
use App\Services\Modules\ModuleManager;

if (!function_exists('is_module_active')) {
    /**
     * Check if a business module is active for the current or given tenant.
     */
    function is_module_active(string $moduleId, ?Tenant $tenant = null): bool
    {
        return app(ModuleManager::class)->isModuleActive($moduleId, $tenant);
    }
}

