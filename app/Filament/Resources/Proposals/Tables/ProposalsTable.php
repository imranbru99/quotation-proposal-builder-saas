<?php

namespace App\Filament\Resources\Proposals\Tables;

use App\Models\Proposal;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProposalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('proposal_number')
                    ->label('Proposal #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(25),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label('Total Value')
                    ->money(fn(Proposal $record) => $record->currency)
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('valid_until')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'signed' => 'success',
                        'converted' => 'purple',
                        'sent' => 'info',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'signed' => 'Signed',
                        'converted' => 'Converted',
                    ]),
                TrashedFilter::make(),
            ])
            ->actions([
                // Direct Electronic Signature Link
                Action::make('sign_page')
                    ->label('E-Sign Page')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->url(fn (Proposal $record): string => route('portal.sign.show', ['signToken' => $record->sign_token]))
                    ->openUrlInNewTab(),

                // Download Unicode PDF
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (Proposal $record): string => route('portal.proposal.pdf', ['token' => $record->view_token]))
                    ->openUrlInNewTab(),

                // Client View
                Action::make('portal')
                    ->label('Client View')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->url(fn (Proposal $record): string => route('portal.proposal.view', ['token' => $record->view_token]))
                    ->openUrlInNewTab(),

                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
