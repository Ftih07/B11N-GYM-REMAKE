<?php

namespace App\Filament\Resources\GymkosResource\Pages;

use App\Filament\Resources\GymkosResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGymkos extends EditRecord
{
    protected static string $resource = GymkosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
