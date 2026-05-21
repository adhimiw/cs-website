<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactSubmissionResource\Pages;
use App\Models\ContactSubmission;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ContactSubmissionResource extends Resource
{
    protected static ?string $model = ContactSubmission::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Form Submissions';

    protected static ?string $modelLabel = 'Form Submission';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('form_name')
                            ->disabled(),
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('phone'),
                        Forms\Components\TextInput::make('subject'),
                        Forms\Components\Textarea::make('message')
                            ->columnSpanFull()
                            ->required(),
                        Forms\Components\Toggle::make('thank_you_sent'),
                        Forms\Components\Toggle::make('admin_notified'),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('subject')
                    ->limit(30),
                Tables\Columns\TextColumn::make('country')
                    ->sortable(),
                Tables\Columns\IconColumn::make('thank_you_sent')
                    ->boolean(),
                Tables\Columns\IconColumn::make('admin_notified')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('thank_you_sent'),
                Tables\Filters\TernaryFilter::make('admin_notified'),
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
            'index' => Pages\ListContactSubmissions::route('/'),
            'create' => Pages\CreateContactSubmission::route('/create'),
            'edit' => Pages\EditContactSubmission::route('/{record}/edit'),
        ];
    }
}
