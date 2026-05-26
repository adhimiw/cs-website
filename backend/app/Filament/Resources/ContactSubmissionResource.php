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
                Section::make('Form Submission Details')
                    ->schema([
                        Forms\Components\Tabs::make('Submission Info')
                            ->tabs([
                                Forms\Components\Tabs\Tab::make('Message Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('form_name')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->required(),
                                        Forms\Components\TextInput::make('phone'),
                                        Forms\Components\TextInput::make('subject')
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('message')
                                            ->columnSpanFull()
                                            ->required()
                                            ->rows(5),
                                        Forms\Components\Toggle::make('thank_you_sent')
                                            ->label('Thank-You Email Sent'),
                                        Forms\Components\Toggle::make('admin_notified')
                                            ->label('Admin Notified'),
                                    ])
                                    ->columns(2),

                                Forms\Components\Tabs\Tab::make('Marketing & Attribution')
                                    ->schema([
                                        Forms\Components\TextInput::make('referrer_source')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('referrer_url')
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('utm_source')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('utm_medium')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('utm_campaign')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('ip_address')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('country')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\TextInput::make('city')
                                            ->disabled()
                                            ->dehydrated(),
                                    ])
                                    ->columns(3),

                                Forms\Components\Tabs\Tab::make('Extra Metadata')
                                    ->schema([
                                        Forms\Components\KeyValue::make('payload')
                                            ->keyLabel('Field Key')
                                            ->valueLabel('Field Value')
                                            ->disabled()
                                            ->dehydrated()
                                            ->columnSpanFull(),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ])
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
