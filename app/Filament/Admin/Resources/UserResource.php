<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->withTrashed();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\Toggle::make('email_verified')
                    ->label('Email Verified')
                    ->helperText("Mark this user's email as verified")
                    ->dehydrateStateUsing(fn ($state) => $state ? now() : null)
                    ->dehydrated(fn ($state) => filled($state))
                    ->default(false),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->required(fn (string $context): bool => $context === 'create')
                    ->maxLength(255),
                Forms\Components\Select::make('role')
                    ->options([
                        'admin' => 'Administrator',
                        'learner' => 'Learner',
                    ])
                    ->required()
                    ->default('learner'),

                Forms\Components\TextInput::make('job_title')
                    ->maxLength(255)
                    ->default('Software Engineer'),
                Forms\Components\FileUpload::make('avatar')
                    ->image(),

                Forms\Components\TextInput::make('employment')
                    ->maxLength(255),
                Forms\Components\TextInput::make('website')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('twitter')
                    ->maxLength(255),
                Forms\Components\TextInput::make('github')
                    ->maxLength(255),
                Forms\Components\TextInput::make('hometown')
                    ->maxLength(255),
                Forms\Components\Textarea::make('bio')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->withTrashed())
            ->modifyQueryUsing(fn ($query) => $query->when(
                request()->query('tableFilters.disabled') === 'true',
                fn ($query) => $query->onlyTrashed()
            ))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'learner' => 'success',
                    }),
                Tables\Columns\IconColumn::make('disabled')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-x-circle')
                    ->falseIcon('heroicon-o-check-circle')
                    ->trueColor('danger')
                    ->falseColor('success')
                    ->getStateUsing(fn (User $record): bool => $record->trashed()),
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
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Administrator',
                        'learner' => 'Learner',
                    ]),
                Tables\Filters\TernaryFilter::make('disabled')
                    ->label('Status')
                    ->placeholder('All users')
                    ->trueLabel('Disabled users')
                    ->falseLabel('Active users')
                    ->queries(
                        true: fn ($query) => $query->onlyTrashed(),
                        false: fn ($query) => $query->whereNull('deleted_at'),
                    ),
            ])
            ->actions([
                Tables\Actions\Action::make('disable')
                    ->label('Disable')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (User $record): void {
                        if ($record->role === 'admin') {
                            Notification::make()
                                ->title('Cannot disable admin')
                                ->body('Administrator accounts cannot be disabled.')
                                ->warning()
                                ->send();

                            return;
                        }

                        $record->delete();
                        $record->logActivityAsAdmin('user_disabled', "User {$record->name} was disabled");

                        Notification::make()
                            ->title('User disabled')
                            ->body('The user has been disabled successfully.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (User $record): bool => ! $record->trashed() && $record->role !== 'admin'),
                Tables\Actions\Action::make('enable')
                    ->label('Enable')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (User $record): void {
                        $record->restore();
                        $record->logActivityAsAdmin('user_enabled', "User {$record->name} was enabled");

                        Notification::make()
                            ->title('User enabled')
                            ->body('The user has been enabled successfully.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (User $record): bool => $record->trashed()),
                Tables\Actions\Action::make('delete')
                    ->label('Delete Permanently')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (User $record): void {
                        $record->forceDelete();
                        $record->logActivityAsAdmin('user_deleted', "User {$record->name} was permanently deleted");

                        Notification::make()
                            ->title('User permanently deleted')
                            ->body('The user has been permanently deleted.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (User $record): bool => $record->trashed() && $record->role !== 'admin'),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    BulkAction::make('delete')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $records->each(function (User $record): void {
                                if ($record->role !== 'admin') {
                                    $record->forceDelete();
                                    $record->logActivityAsAdmin('user_deleted', "User {$record->name} was permanently deleted");
                                }
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('disable')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $records->each(function (User $record): void {
                                if ($record->role !== 'admin') {
                                    $record->delete();
                                    $record->logActivityAsAdmin('user_disabled', "User {$record->name} was disabled");
                                }
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('enable')
                        ->requiresConfirmation()
                        ->action(function (Collection $records): void {
                            $records->each(function (User $record): void {
                                $record->restore();
                                $record->logActivityAsAdmin('user_enabled', "User {$record->name} was enabled");
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])->checkIfRecordIsSelectableUsing(fn (User $record): bool => $record->role !== 'admin');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
