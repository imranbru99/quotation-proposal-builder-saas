<?php

namespace App\Filament\Platform\Resources\Tenants\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('plan_id')
                    ->relationship('plan', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                TextInput::make('company_email')
                    ->email(),
                TextInput::make('company_phone')
                    ->tel(),
                TextInput::make('website')
                    ->url(),
                TextInput::make('tax_id'),
                TextInput::make('vat_id'),
                Textarea::make('address')
                    ->columnSpanFull(),
                TextInput::make('city'),
                TextInput::make('state'),
                TextInput::make('postal_code'),
                TextInput::make('country')
                    ->required()
                    ->default('US'),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                TextInput::make('currency_symbol')
                    ->required()
                    ->default('$'),
                TextInput::make('logo_path'),
                TextInput::make('signature_path'),
                TextInput::make('primary_color')
                    ->required()
                    ->default('#2563eb'),
                TextInput::make('plan_status')
                    ->required()
                    ->default('active'),
                DateTimePicker::make('trial_ends_at'),
                DateTimePicker::make('subscription_ends_at'),
                Textarea::make('settings')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
                Toggle::make('is_demo')
                    ->required(),
            ]);
    }
}
