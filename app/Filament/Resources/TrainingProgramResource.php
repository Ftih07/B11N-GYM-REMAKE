<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingProgramResource\Pages;
use App\Models\TrainingProgram;
use App\Models\Gymkos;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Actions\ReplicateAction; // Import Replicate Action

class TrainingProgramResource extends Resource
{
    // --- NAVIGATION SETTINGS ---
    protected static ?string $navigationGroup = 'Training Program';
    protected static ?string $model = TrainingProgram::class;
    protected static ?string $navigationIcon = 'heroicon-o-fire'; // Icon: Fire/Energy
    protected static ?int $navigationSort = 7;

    // --- FORM CONFIGURATION ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title Category Training')
                    ->required(),

                // WYSIWYG Editor for Description
                Forms\Components\RichEditor::make('description')
                    ->label('Description Category Training')
                    ->required()
                    ->maxLength(2000),

                Forms\Components\FileUpload::make('image')
                    ->label('Image Category Training')
                    ->required()
                    ->directory('training_program'),

                // Relationships
                Forms\Components\Select::make('gymkos_id')
                    ->label('Gymkos')
                    ->relationship('gymkos', 'name')
                    ->required(),

                Forms\Components\Select::make('category_trainings_id')
                    ->label('Category Training')
                    ->relationship('categorytraining', 'title')
                    ->required(),
            ]);
    }

    // --- TABLE CONFIGURATION ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->label('Title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('description')->label('Description')->limit(50),
                ImageColumn::make('image')->disk('public')->square(),

                Tables\Columns\TextColumn::make('categorytraining.title')->label('Category'),
                Tables\Columns\TextColumn::make('gymkos.name')->label('Gym/Kos')->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // === CUSTOM REPLICATE ACTION ===
                // Allows user to clone a program to a DIFFERENT Gym
                ReplicateAction::make()
                    ->label('Duplicate ke Gym Lain')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('warning')
                    ->modalHeading('Duplikasi Program Latihan')
                    ->modalDescription('Pilih Gym tujuan. Semua data (Title, Deskripsi, Gambar) akan disalin.')
                    ->form([
                        // Modal Form: Select Target Gym
                        Forms\Components\Select::make('gymkos_id')
                            ->label('Pilih Gym Tujuan')
                            ->options(Gymkos::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                    ])
                    // Logic: Override the 'gymkos_id' with the selected one from the modal
                    ->beforeReplicaSaved(function (TrainingProgram $replica, array $data) {
                        $replica->gymkos_id = $data['gymkos_id'];
                    })
                    ->successNotificationTitle('Program berhasil diduplikasi ke Gym lain!'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingPrograms::route('/'),
            'create' => Pages\CreateTrainingProgram::route('/create'),
            'edit' => Pages\EditTrainingProgram::route('/{record}/edit'),
        ];
    }
}
