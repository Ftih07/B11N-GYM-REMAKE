<?php

namespace App\Filament\Resources\FinanceResource\Pages;

use App\Filament\Resources\FinanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets; // 1. Tambahkan use ini

class ListFinances extends ListRecords
{
    use ExposesTableToWidgets; // 2. Tambahkan trait ini di dalam class

    protected static string $resource = FinanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    // Pastikan widget abang dipanggil di sini
    protected function getHeaderWidgets(): array
    {
        return [
            FinanceResource\Widgets\FinanceStats::class, 
        ];
    }
}