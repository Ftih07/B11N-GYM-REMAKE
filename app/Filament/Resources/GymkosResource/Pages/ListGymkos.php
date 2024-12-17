<?php

namespace App\Filament\Resources\GymkosResource\Pages;

use App\Filament\Resources\GymkosResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGymkos extends ListRecords
{
    protected static string $resource = GymkosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
