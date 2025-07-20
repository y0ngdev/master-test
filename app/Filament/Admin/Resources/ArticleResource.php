<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Spatie\FilamentMarkdownEditor\MarkdownEditor;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug($state)))
                    ->required()
                    ->maxLength(191),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(191),

                MarkdownEditor::make('body')
                    ->fileAttachmentsVisibility('public')
                    ->fileAttachmentsDirectory('articles')
                    ->required()
                    ->columnSpanFull()
                    ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('excerpt', Str::excerpt($state))),
                Forms\Components\Textarea::make('excerpt')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('seo_title')
                    ->maxLength(191),
                Forms\Components\Select::make('author_id')
                    ->label('Author')
                    ->options(
                        User::all()
                            ->mapWithKeys(fn ($user) => [
                                $user->id => $user->name
                                    ?? $user->username
                                        ?? $user->email,
                            ])
                            ->toArray()
                    )
                    ->searchable()
                    ->required(),

                Forms\Components\Textarea::make('meta_description')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('meta_keywords')
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'DRAFT' => 'Draft',
                        'PUBLISHED' => 'Published',
                        'ARCHIVED' => 'Archived',
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->label('Author')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),

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
            'index' => Pages\ListArticle::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }
}
