<?php

namespace App\Filament\Resources\CategoryTrainingResource\Pages;

use App\Filament\Resources\CategoryTrainingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCategoryTraining extends EditRecord
{
    protected static string $resource = CategoryTrainingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
