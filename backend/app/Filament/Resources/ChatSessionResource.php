<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChatSessionResource\Pages;
use App\Models\ChatSession;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;

class ChatSessionResource extends Resource
{
    protected static ?string $model = ChatSession::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Chat Sessions';

    protected static ?string $modelLabel = 'Chat Session';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Visitor Details & Location')
                    ->schema([
                        Forms\Components\TextInput::make('session_uuid')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('ip_address')
                            ->disabled(),
                        Forms\Components\TextInput::make('country')
                            ->disabled(),
                        Forms\Components\TextInput::make('region')
                            ->disabled(),
                        Forms\Components\TextInput::make('city')
                            ->disabled(),
                    ])
                    ->columns(2),

                Section::make('Referrer & Attribution')
                    ->schema([
                        Forms\Components\TextInput::make('referrer_source')
                            ->disabled(),
                        Forms\Components\TextInput::make('referrer_url')
                            ->disabled(),
                        Forms\Components\TextInput::make('utm_source')
                            ->disabled(),
                        Forms\Components\TextInput::make('utm_medium')
                            ->disabled(),
                        Forms\Components\TextInput::make('utm_campaign')
                            ->disabled(),
                        Forms\Components\Toggle::make('is_qualified')
                            ->disabled(),
                    ])
                    ->columns(2),

                Section::make('Conversation Transcript')
                    ->schema([
                        Forms\Components\Placeholder::make('transcript')
                            ->label('')
                            ->content(function ($record) {
                                if (!$record) return 'No messages.';
                                $html = '<div class="space-y-4 max-h-[500px] overflow-y-auto p-4 bg-gray-900 border border-gray-800 rounded-xl flex flex-col">';
                                foreach ($record->messages()->orderBy('created_at', 'asc')->get() as $msg) {
                                    $isUser = $msg->role === 'user';
                                    $bg = $isUser ? 'bg-indigo-600/10 border-indigo-500/20 text-indigo-200 align-self-end' : 'bg-gray-850 border-gray-750 text-gray-200';
                                    $sender = $isUser ? 'Visitor' : 'AI Assistant';
                                    $align = $isUser ? 'ml-auto text-right' : 'mr-auto';
                                    $time = $msg->created_at->format('M d, H:i');

                                    $html .= "<div class='flex flex-col max-w-[80%] {$align}'>";
                                    $html .= "  <span class='text-[10px] text-gray-500 mb-1'>{$sender} • {$time}</span>";
                                    $html .= "  <div class='p-3 border rounded-xl {$bg} text-xs leading-relaxed whitespace-pre-wrap text-left'>{$msg->content}</div>";
                                    $html .= "</div>";
                                }
                                $html .= '</div>';
                                return new \Illuminate\Support\HtmlString($html);
                            })
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('session_uuid')
                    ->limit(8)
                    ->label('UUID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->sortable(),
                Tables\Columns\TextColumn::make('city')
                    ->sortable(),
                Tables\Columns\TextColumn::make('referrer_source')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_qualified')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_qualified'),
            ])
            ->actions([
                Actions\EditAction::make()->label('View Chat'),
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
            'index' => Pages\ListChatSessions::route('/'),
            'edit' => Pages\EditChatSession::route('/{record}/edit'),
        ];
    }
}
