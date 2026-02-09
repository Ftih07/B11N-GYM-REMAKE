<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BlogResource extends Resource
{
    // --- NAVIGATION SETTINGS ---

    // Show total count of blogs next to the sidebar menu
    public static function getNavigationBadge(): ?string
    {
        return Blog::count();
    }

    // Sidebar grouping and ordering
    protected static ?string $navigationGroup = 'General Management Website';
    protected static ?int $navigationSort = 5;

    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // --- FORM CONFIGURATION (Create/Edit) ---
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->required()->maxLength(255),

                // Slug Configuration
                Forms\Components\TextInput::make('slug')
                    ->unique(ignoreRecord: true) // Ensure unique slug but ignore current record during edit
                    ->disabled() // Prevent manual editing by admin
                    ->dehydrated(), // REQUIRED: Ensures value is saved to DB even if disabled

                Forms\Components\RichEditor::make('content')->required(),

                // Upload image to 'storage/app/public/blog'
                Forms\Components\FileUpload::make('image')->required()->directory('blog'),

                Forms\Components\Select::make('status')
                    ->options([
                        'unpublish' => 'Unpublish',
                        'publish' => 'Publish',
                    ])
                    ->required(),
            ]);
    }

    // --- TABLE CONFIGURATION (List View) ---
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),

                // Show short description (max 50 chars)
                Tables\Columns\TextColumn::make('content')
                    ->label('Description')
                    ->limit(50),

                Tables\Columns\TextColumn::make('status')->sortable(),
                Tables\Columns\ImageColumn::make('image'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
            ])
            ->filters([
                // Filter dropdown for status
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

    // --- PAGE ROUTES REGISTER ---
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
