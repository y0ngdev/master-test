<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\LessonResource\Pages;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Unique;
use Spatie\FilamentMarkdownEditor\MarkdownEditor;

// use App\Services\VimeoService;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\TextInput::make('title')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (Set $set, ?string $state): mixed => $set('slug', Str::slug($state)))
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(191),

                Forms\Components\Select::make('module_id')
                    ->relationship('module', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),

                Forms\Components\TextInput::make('position')
                    ->numeric()
                    ->unique(modifyRuleUsing: fn (Unique $rule, callable $get) => $rule
                        ->where('module_id', $get('module_id'))
                        ->where('position', $get('position')))
                    ->default(0),

                MarkdownEditor::make('description')
                    ->label('Video Summary')
                    ->fileAttachmentsVisibility('public')
                    ->required()
                    ->placeholder('here you can add all that entails to the video. Summaries, transcription, anything that the video needs')
                    ->columnSpanFull(),

                Forms\Components\FileUpload::make('video')
                    ->disk('local')
                    ->label('Video File')
                    ->directory('lessons-temp')
                    ->moveFiles()
                    ->visibility('private')
                    ->hiddenOn('edit')
                    ->required(fn (string $context): bool => $context === 'create'),

                Forms\Components\Hidden::make('status')->default('processing'),
                Forms\Components\Hidden::make('disk')->default('local'),

                Forms\Components\TextInput::make('duration')
                    ->numeric()
                    ->label('Duration (optional)')
                    ->default(0)
                    ->suffix('seconds')
                    ->disabled()
                    ->dehydrated(),

                Forms\Components\Toggle::make('can_preview')
                    ->label('Free Preview')
                    ->required()
                    ->default(false),

                Forms\Components\Toggle::make('is_published')
                    ->label('Published?')
                    ->required()
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('position')
            ->defaultSort('position')
            ->columns([
                Tables\Columns\TextColumn::make('module.title')
                    ->label('Module')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('formatted_duration')
                    ->label('Duration')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_pre-viewable')
                    ->boolean()
                    ->label('Free Preview'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'READY' => 'success',
                        'PROCESSING' => 'warning',
                        'FAILED' => 'danger',
                    }),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean()
                    ->label('Published'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'PROCESSING' => 'Processing',
                        'READY' => 'Ready',
                        'FAILED' => 'Failed',
                    ]),
                Tables\Filters\SelectFilter::make('is_pre-viewable')
                    ->label('Preview Type')
                    ->options([
                        '1' => 'Free Preview',
                        '0' => 'Premium',
                    ]),
                Tables\Filters\SelectFilter::make('is_published')
                    ->label('Publication Status')
                    ->options([
                        '1' => 'Published',
                        '0' => 'Draft',
                    ]),
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
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/create'),
            'edit' => Pages\EditLesson::route('/{record}/edit'),
        ];
    }
}
