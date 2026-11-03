<?php

namespace App\Filament\Resources\AttendancePunches\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AttendancePunchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('tenant_id')
                    ->relationship('tenant', 'name')
                    ->required(),
                Select::make('employee_id')
                    ->relationship('employee', 'id')
                    ->required(),
                DatePicker::make('punch_date')
                    ->required(),
                DateTimePicker::make('check_in'),
                DateTimePicker::make('check_out'),
                TextInput::make('working_hours')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('status')
                    ->required()
                    ->default('present'),
                TextInput::make('source')
                    ->required()
                    ->default('simulated'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
