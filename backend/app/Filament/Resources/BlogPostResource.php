<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogPostResource\Pages;
use App\Models\BlogPost;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BlogPostResource extends Resource
{
    protected static ?string $model = BlogPost::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationLabel = 'Blog Posts';

    protected static ?string $modelLabel = 'Blog Post';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Blog Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                                $operation === 'create' ? $set('slug', Str::slug($state)) : null
                            ),
                        Forms\Components\TextInput::make('slug')
                            ->disabled()
                            ->dehydrated()
                            ->required()
                            ->unique(BlogPost::class, 'slug', ignoreRecord: true),
                        Forms\Components\RichEditor::make('content')
                            ->columnSpanFull()
                            ->required(),
                    ])
                    ->columns(2),

                Section::make('Metadata & Media')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->disk('public')
                            ->directory('blogs')
                            ->visibility('public'),
                        Forms\Components\TextInput::make('author')
                            ->default('ClimbSphere Team'),
                        Forms\Components\DateTimePicker::make('published_at')
                            ->default(now()),
                    ])
                    ->columns(3),

                Section::make('SEO / AEO / GEO Metadata')
                    ->schema([
                        Forms\Components\TextInput::make('seo_meta.seo_title')
                            ->label('SEO Title')
                            ->placeholder('Defaults to blog title if empty')
                            ->columnSpan(2),
                        Forms\Components\TagsInput::make('seo_meta.target_keywords')
                            ->label('Target Keywords')
                            ->placeholder('Add keywords...')
                            ->columnSpan(1),
                        Forms\Components\Textarea::make('seo_meta.seo_description')
                            ->label('SEO Meta Description')
                            ->rows(3)
                            ->columnSpan(3),
                        Forms\Components\Textarea::make('seo_meta.aeo_summary')
                            ->label('AEO Direct Answer / Summary')
                            ->rows(4)
                            ->columnSpanFull()
                            ->helperText('Concise summary that directly answers the primary question of the post.'),
                        Forms\Components\Repeater::make('seo_meta.faqs')
                            ->label('AEO FAQ Schema')
                            ->schema([
                                Forms\Components\TextInput::make('question')
                                    ->required()
                                    ->placeholder('e.g., What is the timeline for migration?'),
                                Forms\Components\Textarea::make('answer')
                                    ->required()
                                    ->rows(3)
                                    ->placeholder('Provide a factual, clear answer...'),
                            ])
                            ->columnSpanFull()
                            ->grid(2)
                            ->itemLabel(fn (array $state): ?string => $state['question'] ?? null),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->disk('public')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('author')
                    ->sortable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListBlogPosts::route('/'),
            'create' => Pages\CreateBlogPost::route('/create'),
            'edit' => Pages\EditBlogPost::route('/{record}/edit'),
        ];
    }
}
