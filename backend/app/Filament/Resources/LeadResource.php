<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
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
                Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('phone'),
                        Forms\Components\TextInput::make('company'),
                        Forms\Components\TextInput::make('project_type'),
                        Forms\Components\TextInput::make('budget'),
                        Forms\Components\TextInput::make('timeline'),
                        Forms\Components\Select::make('lead_status')
                            ->options([
                                'new' => 'New',
                                'qualified' => 'Qualified',
                                'contacted' => 'Contacted',
                                'closed_won' => 'Closed Won',
                                'closed_lost' => 'Closed Lost',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('plan_or_idea')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
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
