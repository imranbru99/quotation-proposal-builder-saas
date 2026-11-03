<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Proposal;
use App\Models\Quotation;
use App\Services\Modules\ModuleManager;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class BusinessStatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tenant = auth()->user()?->tenant;
        $currency = $tenant?->currency_symbol ?? '$';

        $totalQuotes = Quotation::count();
        $acceptedQuotes = Quotation::whereIn('status', ['accepted', 'converted'])->count();
        $totalQuotesValue = Quotation::sum('grand_total');

        $totalProposals = Proposal::count();
        $signedProposals = Proposal::where('status', 'signed')->count();

        $totalInvoices = Invoice::count();
        $totalRevenue = Payment::sum('amount');
        $outstandingBalance = Invoice::where('status', '!=', 'paid')->sum('balance_due');

        $manager = app(ModuleManager::class);
        $activeModulesCount = count($manager->getActiveModuleIds($tenant));

        return [
            Stat::make('Total Quotations', "{$totalQuotes}")
                ->description("{$acceptedQuotes} Accepted / Converted • {$currency} " . number_format($totalQuotesValue, 2))
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Proposals & E-Signatures', "{$totalProposals}")
                ->description("{$signedProposals} Executed with SHA-256 Audit")
                ->descriptionIcon('heroicon-m-pencil-square')
                ->color('info'),

            Stat::make('Total Revenue Collected', "{$currency} " . number_format($totalRevenue, 2))
                ->description("Across {$totalInvoices} Invoices")
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Outstanding Invoices', "{$currency} " . number_format($outstandingBalance, 2))
                ->description("Awaiting customer settlement")
                ->descriptionIcon('heroicon-m-clock')
                ->color($outstandingBalance > 0 ? 'warning' : 'success'),

            Stat::make('Active Business Modules', "{$activeModulesCount} Active")
                ->description("Zero data loss on module toggle")
                ->descriptionIcon('heroicon-m-puzzle-piece')
                ->color('primary'),
        ];
    }
}

