<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Select::make('invoice_id')
                    ->relationship('invoice', 'title')
                    ->required(),
                Select::make('customer_id')
                    ->relationship('customer', 'name')
                    ->required(),
                Select::make('recorded_by_id')
                    ->relationship('recordedBy', 'name'),
                TextInput::make('payment_number')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                DatePicker::make('payment_date')
                    ->required(),
                TextInput::make('payment_method')
                    ->required()
                    ->default('bank_transfer'),
                TextInput::make('transaction_reference'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('receipt_number'),
            ]);
    }
}
