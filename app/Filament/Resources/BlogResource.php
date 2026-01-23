<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource\RelationManagers;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BlogResource extends Resource
{
    public static function getNavigationBadge(): ?string
    {
        return Blog::count();
    }

    protected static ?string $navigationGroup = 'General Management Website';
    protected static ?int $navigationSort = 5;

    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('title')->required()->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->unique(ignoreRecord: true) // Biar validasi unique jalan bener pas edit
                    ->disabled() // Opsional: disable kalau gamau admin ngedit slug manual
                    ->dehydrated(), // Wajib ada kalau didisable, biar tetep masuk database
                Forms\Components\RichEditor::make('content')->required(),
                Forms\Components\FileUpload::make('image')->required()->directory('blog'),
                Forms\Components\Select::make('status')
                    ->options([
                        'unpublish' => 'Unpublish',
                        'publish' => 'Publish',
                    ])
                    ->required(),
                Forms\Components\Select::make('gymkos_id')
                    ->relationship('gymkos', 'name') // Assuming Gymkos has a `name` column
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('content')
                    ->label('Description')
                    ->limit(50),
                Tables\Columns\TextColumn::make('status')->sortable(),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('gymkos.name')->label('Gymkos'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'unpublish' => 'Unpublish',
                        'publish' => 'Publish',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
