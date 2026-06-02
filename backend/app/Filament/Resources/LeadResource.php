<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'CRM Leads';

    protected static ?string $modelLabel = 'CRM Lead';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('CRM Lead Profile')
                    ->schema([
                        Tabs::make('Lead Information')
                            ->tabs([
                                Tabs\Tab::make('Contact Details')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->required(),
                                        Forms\Components\TextInput::make('email')
                                            ->email()
                                            ->required(),
                                        Forms\Components\TextInput::make('phone'),
                                        Forms\Components\TextInput::make('company'),
                                    ])
                                    ->columns(2),

                                Tabs\Tab::make('Project Context')
                                    ->schema([
                                        Forms\Components\TextInput::make('project_type')
                                            ->placeholder('e.g., Web App / CRM Integration'),
                                        Forms\Components\TextInput::make('budget')
                                            ->placeholder('e.g., $10k - $20k'),
                                        Forms\Components\TextInput::make('timeline')
                                            ->placeholder('e.g., 3 months'),
                                        Forms\Components\Textarea::make('plan_or_idea')
                                            ->columnSpanFull()
                                            ->rows(4),
                                    ])
                                    ->columns(3),

                                Tabs\Tab::make('Marketing & Attribution')
                                    ->schema([
                                        Forms\Components\TextInput::make('source_type')
                                            ->disabled()
                                            ->dehydrated(),
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

                                Tabs\Tab::make('CRM Logs & System Status')
                                    ->schema([
                                        Forms\Components\Select::make('lead_status')
                                            ->options([
                                                'new' => 'New',
                                                'qualified' => 'Qualified',
                                                'contacted' => 'Contacted',
                                                'closed_won' => 'Closed Won',
                                                'closed_lost' => 'Closed Lost',
                                            ])
                                            ->required(),
                                        Forms\Components\TextInput::make('email_status')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\DateTimePicker::make('email_queued_at')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\DateTimePicker::make('admin_notified_at')
                                            ->disabled()
                                            ->dehydrated(),
                                        Forms\Components\Textarea::make('notes')
                                            ->columnSpanFull()
                                            ->rows(3),
                                    ])
                                    ->columns(2),
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
                Tables\Columns\TextColumn::make('source_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'chat' => 'success',
                        'contact_form' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('project_type'),
                Tables\Columns\TextColumn::make('lead_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'gray',
                        'qualified' => 'info',
                        'contacted' => 'warning',
                        'closed_won' => 'success',
                        'closed_lost' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('email_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sent' => 'success',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('country')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('lead_status')
                    ->options([
                        'new' => 'New',
                        'qualified' => 'Qualified',
                        'contacted' => 'Contacted',
                        'closed_won' => 'Closed Won',
                        'closed_lost' => 'Closed Lost',
                    ]),
                Tables\Filters\SelectFilter::make('source_type')
                    ->options([
                        'chat' => 'Chatbot',
                        'contact_form' => 'Contact Form',
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
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
