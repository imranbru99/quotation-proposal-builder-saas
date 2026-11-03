<?php

namespace App\Filament\Widgets;

use App\Models\Quotation;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentQuotationsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Quotation::latest()->limit(5))
            ->columns([
                TextColumn::make('quotation_number')
                    ->label('Quote #')
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('customer.name')
                    ->label('Customer'),
                TextColumn::make('title')
                    ->limit(30),
                TextColumn::make('issue_date')
                    ->date('M d, Y'),
                TextColumn::make('grand_total')
                    ->label('Total')
                    ->money(fn(Quotation $record) => $record->currency)
                    ->weight('bold'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accepted' => 'success',
                        'converted' => 'purple',
                        'sent' => 'info',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }
}

