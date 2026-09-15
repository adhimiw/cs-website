<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MeetingResource\Pages;
use App\Models\Meeting;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class MeetingResource extends Resource
{
    protected static ?string $model = Meeting::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Meeting Scheduler';

    protected static ?string $modelLabel = 'Scheduled Meeting';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Meeting Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required(),
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required(),
                        Forms\Components\TextInput::make('phone'),
                        Forms\Components\TextInput::make('company'),
                        Forms\Components\DatePicker::make('scheduled_date')
                            ->required(),
                        Forms\Components\TextInput::make('scheduled_time')
                            ->placeholder('e.g. 15:00 or 3:00 PM')
                            ->required(),
                        Forms\Components\Select::make('meeting_type')
                            ->options([
                                'discovery_call' => 'Discovery Call',
                                'strategy_session' => 'Strategy Session',
                                'demo' => 'Product / Platform Demo',
                            ])
                            ->default('discovery_call'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'confirmed' => 'Confirmed',
                                'pending' => 'Pending Confirmation',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('confirmed'),
                        Forms\Components\TextInput::make('timezone')
                            ->default('UTC'),
                        Forms\Components\Textarea::make('topic')
                            ->columnSpanFull()
                            ->rows(3),
                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull()
                            ->rows(3),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('company')
                    ->searchable()
                    ->placeholder('N/A'),
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->date('M j, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('scheduled_time')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('meeting_type')
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state))),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'completed' => 'gray',
                        'cancelled' => 'danger',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M j, H:i')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('scheduled_date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'confirmed' => 'Confirmed',
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeeting::route('/create'),
            'edit' => Pages\EditMeeting::route('/{record}/edit'),
        ];
    }
}
