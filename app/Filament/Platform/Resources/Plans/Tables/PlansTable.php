<?php

namespace App\Filament\Platform\Resources\Plans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PlansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('price_monthly')
                    ->label('Monthly Price')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('price_annual')
                    ->label('Annual Price')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('max_users')
                    ->label('Users')
                    ->sortable(),
                TextColumn::make('max_quotations_monthly')
                    ->label('Quotes / mo')
                    ->sortable(),
                TextColumn::make('max_invoices_monthly')
                    ->label('Invoices / mo')
                    ->sortable(),
                IconColumn::make('is_active')
                    ->boolean(),
                IconColumn::make('is_featured')
                    ->boolean(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
