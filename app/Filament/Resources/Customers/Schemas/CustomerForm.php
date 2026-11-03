<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Client Profile')
                    ->columns(3)
                    ->schema([
                        Select::make('type')
                            ->options([
                                'company' => 'Company / Organization',
                                'individual' => 'Individual / Personal',
                            ])
                            ->default('company')
                            ->required(),
                        TextInput::make('name')
                            ->label('Primary Contact Name')
                            ->required(),
                        TextInput::make('company_name')
                            ->label('Company / Business Name'),
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email(),
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel(),
                        TextInput::make('website')
                            ->url()
                            ->placeholder('https://clientwebsite.com'),
                    ]),

                Section::make('Tax & Billing Details')
                    ->columns(3)
                    ->schema([
                        TextInput::make('tax_number')
                            ->label('Tax / EIN ID'),
                        TextInput::make('vat_number')
                            ->label('VAT ID'),
                        Select::make('currency')
                            ->options([
                                'USD' => 'USD ($)',
                                'EUR' => 'EUR (€)',
                                'GBP' => 'GBP (£)',
                                'BDT' => 'BDT (৳)',
                                'CAD' => 'CAD ($)',
                                'AUD' => 'AUD ($)',
                                'AED' => 'AED (د.إ)',
                            ])
                            ->default('USD')
                            ->required(),
                        Textarea::make('billing_address')
                            ->columnSpan(2),
                        TextInput::make('city'),
                        TextInput::make('state'),
                        TextInput::make('postal_code'),
                        TextInput::make('country')
                            ->default('US')
                            ->maxLength(2),
                        Select::make('status')
                            ->options([
                                'active' => 'Active Client',
                                'lead' => 'Prospective Lead',
                                'inactive' => 'Inactive',
                            ])
                            ->default('active')
                            ->required(),
                    ]),

                Section::make('Client Portal Access')
                    ->columns(2)
                    ->schema([
                        Toggle::make('portal_access_enabled')
                            ->label('Enable Customer Portal Access')
                            ->default(true),
                        Textarea::make('notes')
                            ->placeholder('Internal notes regarding this client...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
