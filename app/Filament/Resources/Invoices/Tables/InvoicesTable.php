<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Models\Invoice;
use App\Models\Payment;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('issue_date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('due_date')
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('grand_total')
                    ->label('Total')
                    ->money(fn(Invoice $record) => $record->currency)
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('amount_paid')
                    ->label('Paid')
                    ->money(fn(Invoice $record) => $record->currency)
                    ->color('success')
                    ->sortable(),
                TextColumn::make('balance_due')
                    ->label('Balance')
                    ->money(fn(Invoice $record) => $record->currency)
                    ->color(fn(Invoice $record) => $record->balance_due > 0 ? 'danger' : 'gray')
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'partially_paid' => 'warning',
                        'overdue' => 'danger',
                        'sent' => 'info',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'sent' => 'Sent',
                        'partially_paid' => 'Partially Paid',
                        'paid' => 'Fully Paid',
                        'overdue' => 'Overdue',
                    ]),
                TrashedFilter::make(),
            ])
            ->actions([
                // Record Payment Action
                Action::make('record_payment')
                    ->label('Record Payment')
                    ->icon('heroicon-o-credit-card')
                    ->color('success')
                    ->visible(fn (Invoice $record): bool => $record->balance_due > 0)
                    ->form([
                        TextInput::make('amount')
                            ->label('Payment Amount')
                            ->numeric()
                            ->required()
                            ->default(fn (Invoice $record) => $record->balance_due),
                        DatePicker::make('payment_date')
                            ->default(now())
                            ->required(),
                        Select::make('payment_method')
                            ->options([
                                'bank_transfer' => 'Bank Wire / Transfer',
                                'credit_card' => 'Credit Card',
                                'cash' => 'Cash',
                                'check' => 'Check',
                                'stripe' => 'Stripe Gateway',
                                'paypal' => 'PayPal',
                                'other' => 'Other Method',
                            ])
                            ->default('bank_transfer')
                            ->required(),
                        TextInput::make('transaction_reference')
                            ->placeholder('e.g. TXN-99882211 or Bank Wire Ref'),
                        Textarea::make('notes')
                            ->placeholder('Notes regarding payment...'),
                    ])
                    ->action(function (Invoice $record, array $data) {
                        Payment::create([
                            'tenant_id' => $record->tenant_id,
                            'invoice_id' => $record->id,
                            'customer_id' => $record->customer_id,
                            'recorded_by_id' => auth()->id(),
                            'amount' => $data['amount'],
                            'currency' => $record->currency,
                            'payment_date' => $data['payment_date'],
                            'payment_method' => $data['payment_method'],
                            'transaction_reference' => $data['transaction_reference'] ?? null,
                            'notes' => $data['notes'] ?? null,
                        ]);

                        $record->refresh();

                        Notification::make()
                            ->title('Payment Recorded!')
                            ->body("Recorded {$record->currency} " . number_format($data['amount'], 2) . ". Remaining balance: {$record->currency} " . number_format($record->balance_due, 2))
                            ->success()
                            ->send();
                    }),

                // Download Unicode PDF
                Action::make('pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->url(fn (Invoice $record): string => route('portal.invoice.pdf', ['token' => $record->view_token]))
                    ->openUrlInNewTab(),

                // Client View
                Action::make('portal')
                    ->label('Client View')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->url(fn (Invoice $record): string => route('portal.invoice.view', ['token' => $record->view_token]))
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
