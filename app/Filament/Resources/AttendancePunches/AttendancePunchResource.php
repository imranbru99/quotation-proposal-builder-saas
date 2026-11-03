<?php

namespace App\Filament\Resources\AttendancePunches;

use App\Filament\Resources\AttendancePunches\Pages\CreateAttendancePunch;
use App\Filament\Resources\AttendancePunches\Pages\EditAttendancePunch;
use App\Filament\Resources\AttendancePunches\Pages\ListAttendancePunches;
use App\Filament\Resources\AttendancePunches\Schemas\AttendancePunchForm;
use App\Filament\Resources\AttendancePunches\Tables\AttendancePunchesTable;
use App\Models\AttendancePunch;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

class AttendancePunchResource extends Resource
{
    protected static ?string $model = AttendancePunch::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-clock';

    protected static string | \UnitEnum | null $navigationGroup = 'HR & Attendance';

    protected static ?int $navigationSort = 2;

    public static function canAccess(): bool
    {
        return is_module_active('attendance');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return is_module_active('attendance');
    }

    public static function form(Schema $schema): Schema
    {
        return AttendancePunchForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendancePunchesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendancePunches::route('/'),
            'create' => CreateAttendancePunch::route('/create'),
            'edit' => EditAttendancePunch::route('/{record}/edit'),
        ];
    }
}
