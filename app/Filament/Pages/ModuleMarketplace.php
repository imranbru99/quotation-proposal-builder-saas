<?php

namespace App\Filament\Pages;

use App\Services\Modules\ModuleManager;
use App\Services\Modules\ModuleRegistry;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ModuleMarketplace extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string | \UnitEnum | null $navigationGroup = 'Business Settings';

    protected static ?string $navigationLabel = 'Modules Marketplace';

    protected static ?string $title = 'Business Modules Marketplace';

    protected static ?int $navigationSort = 10;

    protected string $view = 'filament.pages.module-marketplace';

    public function toggleModule(string $moduleId): void
    {
        $manager = app(ModuleManager::class);
        $tenant = auth()->user()?->tenant;

        if (!$tenant) {
            Notification::make()
                ->title('Tenant Context Error')
                ->body('Unable to resolve active tenant context.')
                ->danger()
                ->send();
            return;
        }

        $isActive = $manager->isModuleActive($moduleId, $tenant);

        if ($isActive) {
            $result = $manager->deactivate($moduleId, $tenant);
            if ($result['success']) {
                Notification::make()
                    ->title('Module Deactivated')
                    ->body($result['message'])
                    ->warning()
                    ->send();
            } else {
                Notification::make()
                    ->title('Deactivation Blocked')
                    ->body($result['message'])
                    ->danger()
                    ->send();
            }
        } else {
            $result = $manager->activate($moduleId, $tenant);
            if ($result['success']) {
                Notification::make()
                    ->title('Module Activated')
                    ->body($result['message'])
                    ->success()
                    ->send();
            } else {
                Notification::make()
                    ->title('Activation Failed')
                    ->body($result['message'])
                    ->danger()
                    ->send();
            }
        }
    }

    public function getViewData(): array
    {
        $registry = app(ModuleRegistry::class);
        $manager = app(ModuleManager::class);
        $tenant = auth()->user()?->tenant;

        $categories = $registry->byCategory();
        $activeIds = $manager->getActiveModuleIds($tenant);

        return [
            'categories' => $categories,
            'activeIds' => $activeIds,
            'tenant' => $tenant,
        ];
    }
}
