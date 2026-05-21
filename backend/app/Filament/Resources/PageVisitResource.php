<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageVisitResource\Pages;
use App\Models\PageVisit;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageVisitResource extends Resource
{
    protected static ?string $model = PageVisit::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $navigationLabel = 'Page Visits';

    protected static ?string $modelLabel = 'Page Visit';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('Visited URL')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('country')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('referrer_source')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                Tables\Columns\TextColumn::make('utm_campaign')
                    ->label('Campaign')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('country')
                    ->options(fn () => PageVisit::whereNotNull('country')->distinct()->pluck('country', 'country')->toArray()),
                Tables\Filters\SelectFilter::make('referrer_source')
                    ->options(fn () => PageVisit::whereNotNull('referrer_source')->distinct()->pluck('referrer_source', 'referrer_source')->toArray()),
            ])
            ->actions([
                Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPageVisits::route('/'),
        ];
    }
}
