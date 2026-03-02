<?php

namespace App\Filament\Resources\SurveyResource\Pages;

use App\Filament\Resources\SurveyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SurveyImport; // Pastikan memanggil class Import yang kita buat sebelumnya
use Filament\Notifications\Notification;

class ListSurveys extends ListRecords
{
    protected static string $resource = SurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // --- ACTION IMPORT ---
            Actions\Action::make('import')
                ->label('Import Data Survei')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('warning') // Warna kuning/orange biar beda sama tombol Create
                ->form([
                    FileUpload::make('file')
                        ->label('Upload File Google Form (.csv / .xlsx)')
                        // Membatasi hanya file CSV atau Excel yang bisa diunggah
                        ->acceptedFileTypes([
                            'text/csv',
                            'text/plain',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel'
                        ])
                        ->directory('imports') // File akan disimpan sementara di folder storage/app/public/imports
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        // Menjalankan proses import dari file yang baru saja diunggah
                        // 'public' adalah disk default yang digunakan Filament
                        Excel::import(new SurveyImport, $data['file'], 'public');

                        // Munculkan notifikasi sukses di pojok kanan atas
                        Notification::make()
                            ->title('Import Berhasil!')
                            ->body('Data survei pelanggan berhasil ditambahkan ke database.')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        // Jika terjadi error saat mapping/import, tampilkan errornya
                        Notification::make()
                            ->title('Gagal Import Data')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            // --- ACTION BAWAAN CREATE ---
            Actions\CreateAction::make(),
        ];
    }
}
