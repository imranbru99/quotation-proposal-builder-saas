<?php

namespace App\Filament\Resources\AutomationRules;

use App\Filament\Resources\AutomationRules\Pages\CreateAutomationRule;
use App\Filament\Resources\AutomationRules\Pages\EditAutomationRule;
use App\Filament\Resources\AutomationRules\Pages\ListAutomationRules;
use App\Filament\Resources\AutomationRules\Schemas\AutomationRuleForm;
use App\Filament\Resources\AutomationRules\Tables\AutomationRulesTable;
use App\Models\AutomationRule;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AutomationRuleResource extends Resource
{
    protected static ?string $model = AutomationRule::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-bolt';

    protected static string | \UnitEnum | null $navigationGroup = 'Business Settings';

    protected static ?int $navigationSort = 11;

    public static function canAccess(): bool
    {
        return is_module_active('automation');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return is_module_active('automation');
    }

    public static function form(Schema $schema): Schema
    {
        return AutomationRuleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AutomationRulesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAutomationRules::route('/'),
            'create' => CreateAutomationRule::route('/create'),
            'edit' => EditAutomationRule::route('/{record}/edit'),
        ];
    }
}
