<?php

namespace App\Filament\Resources\MemberResource\Pages;

use App\Filament\Resources\MemberResource;
use App\Imports\MemberImport; // Tambahan untuk memanggil class import
use Filament\Actions;
use Filament\Actions\Action; // Tambahan untuk bikin custom action
use Filament\Forms\Components\FileUpload; // Tambahan untuk form upload
use Filament\Notifications\Notification; // Tambahan untuk notifikasi sukses/gagal
use Filament\Resources\Pages\ListRecords;
use Maatwebsite\Excel\Facades\Excel; // Tambahan package Laravel Excel
use Illuminate\Support\Facades\Storage;

class ListMembers extends ListRecords
{
    protected static string $resource = MemberResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // TOMBOL IMPORT KITA TAMBAHKAN DI SINI
            Action::make('importExcel')
                ->label('Import Excel')
                ->color('success')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file_excel')
                        ->label('Upload File URUT.csv')
                        ->disk('local')
                        ->directory('imports') // File akan masuk ke storage/app/imports
                        ->acceptedFileTypes([
                            'text/csv',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ])
                        ->required(),
                ])
                ->action(function (array $data) {
                    // Minta Laravel nyariin path aslinya yang paling akurat
                    $filePath = Storage::disk('local')->path($data['file_excel']);

                    try {
                        // Panggil MemberImport di sini (Angka 1 = ID B11N Gym)
                        Excel::import(new MemberImport(2), $filePath);

                        Notification::make()
                            ->title('Berhasil!')
                            ->body('Ribuan data member berhasil masuk database.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Gagal Import')
                            ->body('Ada masalah: ' . $e->getMessage())
                            ->danger()
                            ->send();
                    }

                    // Hapus file excel setelah selesai dibaca
                    if (Storage::disk('local')->exists($data['file_excel'])) {
                        Storage::disk('local')->delete($data['file_excel']);
                    }
                }),

            // INI TOMBOL BAWAAN FILAMENT (Biarkan saja biar tetap bisa nambah manual)
            Actions\CreateAction::make(),
        ];
    }
}
