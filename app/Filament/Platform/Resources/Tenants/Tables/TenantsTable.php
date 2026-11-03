<?php

namespace App\Filament\Platform\Resources\Tenants\Tables;

use App\Models\Tenant;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class TenantsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Company Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('slug')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('plan.name')
                    ->label('Subscription Plan')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                TextColumn::make('company_email')
                    ->label('Email')
                    ->searchable(),
                TextColumn::make('plan_status')
                    ->badge()
                    ->color(fn(string $state) => match($state) {
                        'active' => 'success',
                        'trialing' => 'info',
                        'past_due' => 'danger',
                        default => 'gray',
                    }),
                IconColumn::make('is_active')
                    ->boolean(),
                TextColumn::make('created_at')
                    ->date('M d, Y')
                    ->sortable(),
            ])
            ->actions([
                Action::make('impersonate_owner')
                    ->label('Switch to Tenant')
                    ->icon('heroicon-o-arrow-right-start-on-rectangle')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (Tenant $record) {
                        $owner = User::where('tenant_id', $record->id)->where('role', 'owner')->first()
                            ?? User::where('tenant_id', $record->id)->first();

                        if ($owner) {
                            Auth::login($owner);
                            return redirect('/app');
                        }
                    }),

                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
