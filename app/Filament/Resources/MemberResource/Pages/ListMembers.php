<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Imports\MemberImport;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('importExcel')
                ->label('Import Excel')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    // Pilihan Cabang Gym (Opsional tapi recommended biar fleksibel)
                    Select::make('gymkos_id')
                        ->label('Pilih Cabang (Gym/Kos)')
                        ->options(\App\Models\Gymkos::all()->pluck('name', 'id'))
                        ->default(2)
                        ->required(),

                    FileUpload::make('file_excel')
                        ->label('Upload File Excel / MASTER.xlsx / CSV')
                        ->disk('local')
                        ->directory('imports')
                        ->acceptedFileTypes([
                            'text/csv',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    // --- 1. MENCEGAH TIMEOUT & RAM OVERFLOW ---
                    set_time_limit(0);
                    ini_set('memory_limit', '512M');

                    $filePath = Storage::disk('local')->path($data['file_excel']);

                    try {
                        // --- 2. JALANKAN IMPORT ---
                        Excel::import(new MemberImport($data['gymkos_id']), $filePath);

                        Notification::make()
                            ->title('Berhasil!')
                            ->body('Seluruh data member berhasil diimport ke database.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Import')
                            ->body('Ada masalah: '.$e->getMessage())
                            ->danger()
                            ->send();
                    } finally {
                        // --- 3. BERSIHKAN FILE SETELAH PROSES ---
                        if (Storage::disk('local')->exists($data['file_excel'])) {
                            Storage::disk('local')->delete($data['file_excel']);
                        }
                    }
                }),

            Actions\CreateAction::make(),
        ];
    }
}
