<?php

namespace App\Filament\Resources\Proposals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProposalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Proposal Details')
                    ->columns(3)
                    ->schema([
                        Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('title')
                            ->placeholder('e.g. Enterprise Cloud Modernization & Support')
                            ->required()
                            ->columnSpan(2),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent to Client',
                                'signed' => 'Signed & Executed',
                                'converted' => 'Converted to Invoice',
                            ])
                            ->default('draft')
                            ->required(),
                        DatePicker::make('issue_date')
                            ->default(now())
                            ->required(),
                        DatePicker::make('valid_until')
                            ->default(now()->addDays(30)),
                        Select::make('currency')
                            ->options([
                                'USD' => 'USD ($)',
                                'EUR' => 'EUR (€)',
                                'GBP' => 'GBP (£)',
                                'BDT' => 'BDT (৳)',
                                'AED' => 'AED (د.إ)',
                            ])
                            ->default('USD')
                            ->required(),
                        TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('$')
                            ->required(),
                    ]),

                Section::make('Proposal Modular Sections')
                    ->description('Organize your proposal into executive summary, scope of work, deliverables, and legal terms.')
                    ->schema([
                        Repeater::make('sections')
                            ->relationship('sections')
                            ->schema([
                                Select::make('section_type')
                                    ->options([
                                        'executive_summary' => 'Executive Summary',
                                        'scope_of_work' => 'Scope of Work',
                                        'deliverables' => 'Key Deliverables',
                                        'timeline' => 'Project Timeline',
                                        'terms' => 'Terms & Conditions',
                                        'custom' => 'Custom Section',
                                    ])
                                    ->default('custom')
                                    ->required()
                                    ->columnSpan(4),
                                TextInput::make('title')
                                    ->required()
                                    ->columnSpan(6),
                                Textarea::make('content')
                                    ->rows(4)
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(10)
                            ->defaultItems(2)
                            ->collapsible()
                            ->reorderable(),
                    ]),

                Section::make('Payment & Deliverable Milestones')
                    ->schema([
                        Repeater::make('milestones')
                            ->relationship('milestones')
                            ->schema([
                                TextInput::make('title')
                                    ->required()
                                    ->columnSpan(4),
                                DatePicker::make('due_date')
                                    ->columnSpan(2),
                                TextInput::make('percentage')
                                    ->numeric()
                                    ->suffix('%')
                                    ->columnSpan(2),
                                TextInput::make('amount')
                                    ->numeric()
                                    ->prefix('$')
                                    ->required()
                                    ->columnSpan(2),
                                TextInput::make('description')
                                    ->placeholder('Deliverable criteria for milestone release')
                                    ->columnSpanFull(),
                            ])
                            ->columns(10)
                            ->defaultItems(1)
                            ->collapsible(),
                    ]),
            ]);
    }
}
