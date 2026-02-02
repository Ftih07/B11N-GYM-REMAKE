<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrainingProgramResource\Pages;
use App\Filament\Resources\TrainingProgramResource\RelationManagers;
use App\Models\TrainingProgram;
use App\Models\Gymkos; // Pastikan import Model Gymkos
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\ImageColumn;
// Import ReplicateAction
use Filament\Tables\Actions\ReplicateAction;

class TrainingProgramResource extends Resource
{
    protected static ?string $navigationGroup = 'Training Program';
    protected static ?string $model = TrainingProgram::class;

    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label('Title Category Training')
                    ->required()
                    ->maxLength(255),
                Forms\Components\RichEditor::make('description')
                    ->label('Description Category Training')
                    ->required()
                    ->maxLength(2000),
                Forms\Components\FileUpload::make('image')
                    ->label('Image Category Training')
                    ->required()
                    ->directory('training_program'),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50),
                ImageColumn::make('image')
                    ->disk('public')
                    ->square(),
                Tables\Columns\TextColumn::make('categorytraining.title')
                    ->label('Category')
                    ->sortable(),
                Tables\Columns\TextColumn::make('gymkos.name')
                    ->label('Gym/Kos')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // === FITUR DUPLICATE ===
                ReplicateAction::make()
                    ->label('Duplicate ke Gym Lain') // Label tombol
                    ->icon('heroicon-o-document-duplicate')
                    ->color('warning') // Warna kuning biar beda
                    ->modalHeading('Duplikasi Program Latihan')
                    ->modalDescription('Pilih Gym tujuan. Semua data (Title, Deskripsi, Gambar) akan disalin.')
                    ->form([
                        // Form yang muncul di Pop-up
                        Forms\Components\Select::make('gymkos_id')
                            ->label('Pilih Gym Tujuan')
                            ->options(Gymkos::all()->pluck('name', 'id')) // Ambil list Gym
                            ->searchable()
                            ->required(),
                    ])
                    ->beforeReplicaSaved(function (TrainingProgram $replica, array $data) {
                        // Timpa gymkos_id hasil copy dengan yang dipilih user di form
                        $replica->gymkos_id = $data['gymkos_id'];
                    })
                    ->successNotificationTitle('Program berhasil diduplikasi ke Gym lain!'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
