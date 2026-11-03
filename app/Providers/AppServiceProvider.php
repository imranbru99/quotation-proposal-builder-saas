<?php

namespace App\Providers;

use App\Services\Modules\ModuleManager;
use App\Services\Modules\ModuleRegistry;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TenantContext::class, function () {
            return new TenantContext();
        });

        $this->app->singleton(ModuleRegistry::class, function () {
            return new ModuleRegistry();
        });

        $this->app->singleton(ModuleManager::class, function ($app) {
            return new ModuleManager(
                $app->make(ModuleRegistry::class),
                $app->make(TenantContext::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
