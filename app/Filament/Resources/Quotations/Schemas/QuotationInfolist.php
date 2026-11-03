<?php

namespace App\Filament\Resources\Quotations\Schemas;

use App\Models\Quotation;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuotationInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('tenant.name')
                    ->label('Tenant'),
                TextEntry::make('customer.name')
                    ->label('Customer'),
                TextEntry::make('creator.name')
                    ->label('Creator')
                    ->placeholder('-'),
                TextEntry::make('convertedInvoice.title')
                    ->label('Converted invoice')
                    ->placeholder('-'),
                TextEntry::make('quotation_number'),
                TextEntry::make('title')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('issue_date')
                    ->date(),
                TextEntry::make('expiry_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('currency'),
                TextEntry::make('subtotal')
                    ->numeric(),
                TextEntry::make('discount_type'),
                TextEntry::make('discount_value')
                    ->numeric(),
                TextEntry::make('discount_amount')
                    ->numeric(),
                TextEntry::make('tax_amount')
                    ->numeric(),
                TextEntry::make('grand_total')
                    ->numeric(),
                TextEntry::make('payment_terms')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('delivery_terms')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('internal_notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('terms_and_conditions')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('sent_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('viewed_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('accepted_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('rejected_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('rejection_reason')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('converted_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Quotation $record): bool => $record->trashed()),
            ]);
    }
}
