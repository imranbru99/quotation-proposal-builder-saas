<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Item Information')
                    ->columns(3)
                    ->schema([
                        TextInput::make('name')
                            ->label('Product or Service Name')
                            ->required(),
                        TextInput::make('sku')
                            ->label('SKU / Code')
                            ->placeholder('e.g. SRV-001 or PROD-102'),
                        Select::make('type')
                            ->options([
                                'service' => 'Service (Labor / Consulting)',
                                'product' => 'Physical Product / Material',
                            ])
                            ->default('service')
                            ->required(),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('unit')
                            ->placeholder('e.g. item, hour, day, month')
                            ->default('item')
                            ->required(),
                        Select::make('currency')
                            ->options([
                                'USD' => 'USD ($)',
                                'EUR' => 'EUR (€)',
                                'GBP' => 'GBP (£)',
                                'BDT' => 'BDT (৳)',
                            ])
                            ->default('USD')
                            ->required(),
                    ]),

                Section::make('Pricing & Taxes')
                    ->columns(3)
                    ->schema([
                        TextInput::make('price')
                            ->label('Sales Price')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                        TextInput::make('cost')
                            ->label('Internal Cost')
                            ->numeric()
                            ->prefix('$')
                            ->default(0),
                        TextInput::make('tax_rate')
                            ->label('Default Tax Rate')
                            ->numeric()
                            ->suffix('%')
                            ->default(0),
                    ]),

                Section::make('Inventory Tracking')
                    ->columns(3)
                    ->schema([
                        Toggle::make('track_inventory')
                            ->label('Track Stock Levels')
                            ->default(false),
                        TextInput::make('stock_quantity')
                            ->numeric()
                            ->default(0),
                        TextInput::make('low_stock_threshold')
                            ->numeric()
                            ->default(5),
                        Toggle::make('is_active')
                            ->label('Available for Quotations & Invoices')
                            ->default(true),
                        Textarea::make('description')
                            ->columnSpanFull()
                            ->placeholder('Default line item description...'),
                    ]),
            ]);
    }
}
