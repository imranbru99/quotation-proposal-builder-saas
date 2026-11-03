<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PaymentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('invoice.title')
                    ->label('Invoice'),
                TextEntry::make('customer.name')
                    ->label('Customer'),
                TextEntry::make('recordedBy.name')
                    ->label('Recorded by')
                    ->placeholder('-'),
                TextEntry::make('payment_number'),
                TextEntry::make('amount')
                    ->numeric(),
                TextEntry::make('currency'),
                TextEntry::make('payment_date')
                    ->date(),
                TextEntry::make('payment_method'),
                TextEntry::make('transaction_reference')
                    ->placeholder('-'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('receipt_number')
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
