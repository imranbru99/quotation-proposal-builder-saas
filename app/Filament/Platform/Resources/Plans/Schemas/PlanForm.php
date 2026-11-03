<?php

namespace App\Filament\Platform\Resources\Plans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PlanForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                TextInput::make('price_monthly')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('price_annual')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('max_users')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('max_customers')
                    ->required()
                    ->numeric()
                    ->default(50),
                TextInput::make('max_quotations_monthly')
                    ->required()
                    ->numeric()
                    ->default(20),
                TextInput::make('max_invoices_monthly')
                    ->required()
                    ->numeric()
                    ->default(20),
                TextInput::make('storage_limit_mb')
                    ->required()
                    ->numeric()
                    ->default(500),
                TextInput::make('ai_token_limit_monthly')
                    ->required()
                    ->numeric()
                    ->default(10000),
                Textarea::make('included_modules')
                    ->columnSpanFull(),
                Toggle::make('has_api_access')
                    ->required(),
                Toggle::make('has_custom_branding')
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_featured')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
