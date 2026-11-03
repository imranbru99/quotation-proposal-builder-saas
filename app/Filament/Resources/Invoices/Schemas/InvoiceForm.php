<?php

namespace App\Filament\Resources\Invoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Invoice Overview')
                    ->columns(3)
                    ->schema([
                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('title')
                            ->placeholder('e.g. Services rendered for Project Alpha')
                            ->required(),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent to Client',
                                'partially_paid' => 'Partially Paid',
                                'paid' => 'Paid in Full',
                                'overdue' => 'Overdue',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->required(),
                        DatePicker::make('issue_date')
                            ->default(now())
                            ->required(),
                        DatePicker::make('due_date')
                            ->default(now()->addDays(14))
                            ->required(),
                        Select::make('currency')
                            ->options([
                                'USD' => 'USD ($)',
                                'EUR' => 'EUR (€)',
                                'GBP' => 'GBP (£)',
                                'BDT' => 'BDT (৳)',
                                'AED' => 'AED (د.إ)',
                            ])
                            ->default('USD')
                            ->required(),
                    ]),

                Section::make('Invoice Line Items')
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
                            ->placeholder('e.g. Net 14 Days')
                            ->columnSpanFull(),
                        Textarea::make('notes')
                            ->placeholder('Invoice notes for client...')
                            ->columnSpanFull(),
                        Textarea::make('terms_and_conditions')
                            ->placeholder('Payment terms, bank details, or late payment terms...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
