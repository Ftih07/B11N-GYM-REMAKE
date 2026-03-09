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
use ZipArchive;

class HeicConverter extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationGroup = 'Tools';
    protected static ?int $navigationSort = 8;
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
                    ->description('Gunakan hanya pada saat kebutuhan mendesak, karena proses ini akan memakan beban server besar')
                    ->schema([
                        FileUpload::make('image_file')
                            ->label('Pilih File Gambar')
                            ->multiple()
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
                                    ->default(1080)
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
        $files = $state['image_file'];

        try {

            $manager = new ImageManager(new Driver());

            $zipName = 'compressed-images-' . time() . '.zip';
            $zipPath = storage_path('app/' . $zipName);

            $zip = new ZipArchive;
            $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

            foreach ($files as $file) {

                $path = Storage::disk('local')->path($file);
                $image = $manager->read($path);

                // Resize
                if (!empty($state['width'])) {
                    $image->scaleDown(width: $state['width']);
                }

                $format = $state['format'];
                $quality = (int) $state['quality'];
                $filename = pathinfo($file, PATHINFO_FILENAME);

                if ($format === 'jpg') {
                    $encoded = $image->encode(new JpegEncoder(quality: $quality));
                    $ext = '.jpg';
                } elseif ($format === 'webp') {
                    $encoded = $image->encode(new WebpEncoder(quality: $quality));
                    $ext = '.webp';
                } else {
                    $encoded = $image->encode(new PngEncoder());
                    $ext = '.png';
                }

                $zip->addFromString($filename . $ext, $encoded);

                Storage::disk('local')->delete($file);
            }

            $zip->close();

            $this->form->fill();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {

            Notification::make()
                ->title('Gagal Memproses')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}
