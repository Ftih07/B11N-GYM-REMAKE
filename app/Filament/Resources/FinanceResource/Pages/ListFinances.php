<?php

namespace App\Filament\Resources\FinanceResource\Pages;

use App\Filament\Resources\FinanceResource;
use App\Filament\Resources\FinanceResource\Widgets\FinanceStats; // Import Widget tadi
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFinances extends ListRecords
{
    protected static string $resource = FinanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // --- TAMBAHKAN BAGIAN INI ---
    protected function getHeaderWidgets(): array
    {
        return [
            FinanceStats::class,
        ];
    }
    // ----------------------------
}
