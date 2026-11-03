<?php

namespace App\Filament\Resources\Quotations\Tables;

use App\Models\Quotation;
use App\Services\Documents\QuotationToInvoiceConverter;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class QuotationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('quotation_number')
                    ->label('Quote #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->limit(25)
                    ->toggleable(),
                TextColumn::make('issue_date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('expiry_date')
                    ->date('M d, Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('grand_total')
                    ->label('Total')
                    ->money(fn(Quotation $record) => $record->currency)
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'accepted' => 'success',
                        'converted' => 'purple',
                        'sent', 'approved' => 'info',
                        'rejected' => 'danger',
                        'expired', 'cancelled' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'accepted' => 'Accepted',
                        'converted' => 'Converted',
                        'rejected' => 'Rejected',
                    ]),
                TrashedFilter::make(),
            ])
            ->actions([
                // 1-Click Convert to Invoice Action
                Action::make('convert_to_invoice')
                    ->label('Convert to Invoice')
                    ->icon('heroicon-o-banknotes')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Convert Quotation to Invoice')
                    ->modalDescription('This will create an immutable financial invoice snapshot preserving all line items, taxes, discounts, and currency. Proceed?')
                    ->visible(fn (Quotation $record): bool => $record->canBeConverted())
                    ->action(function (Quotation $record) {
                        try {
                            $converter = app(QuotationToInvoiceConverter::class);
                            $invoice = $converter->convert($record);

                            Notification::make()
                                ->title('Quotation Converted!')
                                ->body("Invoice #{$invoice->invoice_number} created successfully.")
                                ->success()
                                ->send();
                        } catch (\Throwable $e) {
                            Notification::make()
                                ->title('Conversion Failed')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                // Download Unicode PDF
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (Quotation $record): string => route('portal.quote.pdf', ['token' => $record->view_token]))
                    ->openUrlInNewTab(),

                // Public Client Review Link
                Action::make('portal')
                    ->label('Client View')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->url(fn (Quotation $record): string => route('portal.quote.view', ['token' => $record->view_token]))
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
