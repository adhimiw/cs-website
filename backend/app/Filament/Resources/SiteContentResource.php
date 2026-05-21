<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteContentResource\Pages;
use App\Models\SiteContent;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class SiteContentResource extends Resource
{
    protected static ?string $model = SiteContent::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'CMS Contents';

    protected static ?string $modelLabel = 'CMS Content';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('page')
                            ->required(),
                        Forms\Components\TextInput::make('section')
                            ->required(),
                        Forms\Components\TextInput::make('key')
                            ->required(),
                        Forms\Components\Select::make('type')
                            ->options([
                                'text' => 'Plain Text',
                                'textarea' => 'Long Textarea',
                                'richtext' => 'Rich WYSIWYG Text',
                                'image' => 'Image URL',
                                'html' => 'HTML Block',
                            ])
                            ->required()
                            ->reactive(),
                        Forms\Components\FileUpload::make('value')
                            ->image()
                            ->directory('site_contents')
                            ->visibility('public')
                            ->visible(fn (Get $get): bool => $get('type') === 'image')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('value')
                            ->columnSpanFull()
                            ->hidden(fn (Get $get): bool => $get('type') === 'image')
                            ->required(),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('section')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->limit(50),
                Tables\Columns\TextColumn::make('type')
                    ->badge(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('page')
                    ->options(fn () => SiteContent::whereNotNull('page')->distinct()->pluck('page', 'page')->toArray()),
            ])
            ->defaultGroup('page')
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
            'index' => Pages\ListSiteContents::route('/'),
            'create' => Pages\CreateSiteContent::route('/create'),
            'edit' => Pages\EditSiteContent::route('/{record}/edit'),
        ];
    }
}
