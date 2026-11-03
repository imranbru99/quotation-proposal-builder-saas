<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Invoice;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class InvoiceInfolist
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
                TextEntry::make('sourceQuotation.title')
                    ->label('Source quotation')
                    ->placeholder('-'),
                TextEntry::make('sourceProposal.title')
                    ->label('Source proposal')
                    ->placeholder('-'),
                TextEntry::make('invoice_number'),
                TextEntry::make('title')
                    ->placeholder('-'),
                TextEntry::make('status'),
                TextEntry::make('issue_date')
                    ->date(),
                TextEntry::make('due_date')
                    ->date(),
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
                TextEntry::make('amount_paid')
                    ->numeric(),
                TextEntry::make('balance_due')
                    ->numeric(),
                TextEntry::make('payment_terms')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('terms_and_conditions')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('sent_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('paid_at')
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
                    ->visible(fn (Invoice $record): bool => $record->trashed()),
            ]);
    }
}
