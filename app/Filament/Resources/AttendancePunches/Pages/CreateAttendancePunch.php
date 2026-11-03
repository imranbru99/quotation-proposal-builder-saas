<?php

namespace App\Filament\Resources\AttendancePunches\Pages;

use App\Filament\Resources\AttendancePunches\AttendancePunchResource;
use Filament\Resources\Pages\CreateRecord;

class CreateAttendancePunch extends CreateRecord
{
    protected static string $resource = AttendancePunchResource::class;
}
