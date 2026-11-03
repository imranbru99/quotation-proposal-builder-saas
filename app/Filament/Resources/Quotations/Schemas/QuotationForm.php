<?php

namespace App\Filament\Resources\Quotations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class QuotationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Quotation Overview')
                    ->columns(3)
                    ->schema([
                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('title')
                            ->placeholder('e.g. Website Redesign & SEO')
                            ->required(),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'pending_approval' => 'Pending Approval',
                                'approved' => 'Approved',
                                'sent' => 'Sent to Client',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                            ])
                            ->default('draft')
                            ->required(),
                        DatePicker::make('issue_date')
                            ->default(now())
                            ->required(),
                        DatePicker::make('expiry_date')
                            ->default(now()->addDays(14)),
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
                    ]),

                Section::make('Line Items')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                TextInput::make('item_name')
                                    ->required()
                                    ->columnSpan(4),
                                TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->columnSpan(2),
                                TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->columnSpan(2),
                                TextInput::make('tax_percentage')
                                    ->numeric()
                                    ->suffix('%')
                                    ->default(0)
                                    ->columnSpan(2),
                                TextInput::make('description')
                                    ->placeholder('Detailed specifications or scope item')
                                    ->columnSpan(10),
                            ])
                            ->columns(10)
                            ->defaultItems(1)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Section::make('Discounts & Terms')
                    ->columns(2)
                    ->schema([
                        Select::make('discount_type')
                            ->options([
                                'percentage' => 'Percentage (%)',
                                'fixed' => 'Fixed Amount',
                            ])
                            ->default('percentage'),
                        TextInput::make('discount_value')
                            ->numeric()
                            ->default(0),
                        TextInput::make('payment_terms')
                            ->placeholder('e.g. 50% upfront deposit, balance upon delivery')
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->placeholder('Notes for client review...')
                            ->columnSpanFull(),
                        Textarea::make('terms_and_conditions')
                            ->placeholder('Standard business terms and conditions...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
