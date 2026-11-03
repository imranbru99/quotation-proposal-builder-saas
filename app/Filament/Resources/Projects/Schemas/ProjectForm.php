<?php

namespace App\Filament\Resources\Projects\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Select::make('customer_id')
                    ->relationship('customer', 'name'),
                Select::make('manager_id')
                    ->relationship('manager', 'name'),
                TextInput::make('name')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('in_progress'),
                DatePicker::make('start_date'),
                DatePicker::make('deadline'),
                TextInput::make('budget')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }
}
