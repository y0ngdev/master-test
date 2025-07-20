<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PlanResource\Pages;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationGroup = 'Payments';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Plan Details')
                    ->description('Below are the basic details for each plan including name, description, and features')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(191),
                        //                        Forms\Components\TextInput::make('sort_order')
                        //                            ->required()
                        //                            ->numeric()
                        //                            ->default(0),
                        Forms\Components\Textarea::make('short_description')
                            ->required()
                            ->placeholder('Enter a short description of the plan')
                            ->columnSpan([
                                'default' => 2,
                                'lg' => 1,
                            ]),
                        Forms\Components\TagsInput::make('features')
                            ->reorderable()
                            ->splitKeys(['Tab', 'Enter'])
                            ->placeholder('New feature')
                            ->columnSpan([
                                'default' => 2,
                                'lg' => 1,
                            ]),

                    ])->columns(2),

                Section::make('Plan Pricing')
                    ->description('Add the pricing details for your plans below')
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->required()

                            ->prefix('$'),

                        Forms\Components\KeyValue::make('gateway_meta')
                            ->name('Price id')
                            ->required()
                            ->keyLabel('Payment Provider')
                            ->valueLabel('Price/Product ID')
                            ->addActionLabel('Add Price ID')
                            ->addable(false)
                            ->deletable(false)
                            ->keyPlaceholder('stripe')
                            ->valuePlaceholder('price_abc123'),

                    ])->columns(2),

                Section::make('Plan Status')
                    ->description('Make the plan featured or active/inactive')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->required()
                            ->default(false),

                        Forms\Components\Toggle::make('is_featured')
                            ->required()
                            ->default(false),

                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\BooleanColumn::make('is_active')

                    ->sortable(),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    //                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlans::route('/'),
            //            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}
