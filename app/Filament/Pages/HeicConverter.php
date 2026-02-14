<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\Encoders\PngEncoder;

class HeicConverter extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-photo'; // Ganti icon biar lebih umum
    protected static ?string $navigationLabel = 'Image Tool'; // Ganti Label
    protected static ?string $title = 'Compress & Convert Gambar'; // Ganti Title

    protected static string $view = 'filament.pages.heic-converter';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Upload Gambar')
                    ->description('Bisa untuk HEIC (iPhone), JPG, PNG, atau WEBP.')
                    ->schema([
                        FileUpload::make('image_file')
                            ->label('Pilih File Gambar')
                            ->acceptedFileTypes([
                                'image/heic',
                                'image/heif',
                                'application/octet-stream', 
                                'image/jpeg',
                                'image/png',
                                'image/webp', 
                            ])
                            ->required()
                            ->disk('local')
                            ->directory('temp-uploads')
                            ->maxSize(20480), 

                        Grid::make(3)
                            ->schema([
                                Select::make('format')
                                    ->label('Format Output')
                                    ->options([
                                        'webp' => 'WEBP (Paling Ringan - Recommended)',
                                        'jpg' => 'JPG (Cocok untuk Foto)',
                                        'png' => 'PNG (Transparan/Lossless)',
                                    ])
                                    ->default('webp') 
                                    ->required()
                                    ->live(),

                                TextInput::make('width')
                                    ->label('Resize Lebar (px)')
                                    ->numeric()
                                    ->placeholder('Contoh: 1080')
                                    ->helperText('Wajib isi 1080/800 jika ingin size turun drastis!'),

                                TextInput::make('quality')
                                    ->label('Kualitas (0-100)')
                                    ->numeric()
                                    ->default(80)
                                    ->minValue(10)
                                    ->maxValue(100)
                                    ->hidden(fn($get) => $get('format') === 'png'),
                            ]),
                    ])
            ])
            ->statePath('data');
    }

    public function convert()
    {
        $state = $this->form->getState();
        $path = Storage::disk('local')->path($state['image_file']);

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($path);

            // 1. Resize Logic
            if (!empty($state['width'])) {
                $image->scaleDown(width: $state['width']);
            }

            // 2. Encode Logic
            $format = $state['format'];
            $quality = (int) $state['quality'];
            $filename = 'compressed-' . time();

            if ($format === 'jpg') {
                $encoded = $image->encode(new JpegEncoder(quality: $quality));
                $mime = 'image/jpeg';
                $ext = '.jpg';
            } elseif ($format === 'webp') {
                $encoded = $image->encode(new WebpEncoder(quality: $quality));
                $mime = 'image/webp';
                $ext = '.webp';
            } else {
                $encoded = $image->encode(new PngEncoder());
                $mime = 'image/png';
                $ext = '.png';
            }

            Storage::disk('local')->delete($state['image_file']);
            $this->form->fill();

            return response()->streamDownload(function () use ($encoded) {
                echo $encoded;
            }, $filename . $ext, ['Content-Type' => $mime]);
        } catch (\Exception $e) {
            Notification::make()
                ->title('Gagal Memproses')
                ->body('Pastikan file gambar valid. Error: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }
}
