<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Models\Service;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-square-3-stack-3d';

    protected static ?string $navigationLabel = 'Services';

    protected static ?string $modelLabel = 'Service';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Service Details')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->options([
                                'business' => 'For Businesses',
                                'partner' => 'For Technology Partners',
                            ])
                            ->required(),
                        Forms\Components\TagsInput::make('tags')
                            ->placeholder('New Tag')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull()
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Media & Ordering')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('services')
                            ->visibility('public'),
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'business' => 'primary',
                        'partner' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'business' => 'For Businesses',
                        'partner' => 'For Technology Partners',
                    ]),
            ])
            ->actions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
