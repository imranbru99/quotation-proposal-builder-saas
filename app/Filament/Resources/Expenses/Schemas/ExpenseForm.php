<?php

namespace App\Filament\Resources\Expenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Select::make('category_id')
                    ->relationship('category', 'name'),
                Select::make('project_id')
                    ->relationship('project', 'name'),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                TextInput::make('title')
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('currency')
                    ->required()
                    ->default('USD'),
                DatePicker::make('expense_date')
                    ->required(),
                TextInput::make('payment_method')
                    ->required()
                    ->default('company_card'),
                TextInput::make('reference_number'),
                TextInput::make('receipt_path'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('approved'),
            ]);
    }
}
