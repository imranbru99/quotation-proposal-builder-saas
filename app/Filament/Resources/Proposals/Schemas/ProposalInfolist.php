<?php

namespace App\Filament\Resources\Proposals\Schemas;

use App\Models\Proposal;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProposalInfolist
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
                TextEntry::make('quotation.title')
                    ->label('Quotation')
                    ->placeholder('-'),
                TextEntry::make('convertedInvoice.title')
                    ->label('Converted invoice')
                    ->placeholder('-'),
                TextEntry::make('proposal_number'),
                TextEntry::make('title'),
                TextEntry::make('status'),
                TextEntry::make('currency'),
                TextEntry::make('total_amount')
                    ->numeric(),
                TextEntry::make('issue_date')
                    ->date(),
                TextEntry::make('valid_until')
                    ->date()
                    ->placeholder('-'),
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
                TextEntry::make('signed_at')
                    ->dateTime()
                    ->placeholder('-'),
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
                    ->visible(fn (Proposal $record): bool => $record->trashed()),
            ]);
    }
}
