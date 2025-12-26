<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make('Konten')
                        ->columnSpan(2)
                        ->schema([
                            TextInput::make('title')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                            TextInput::make('slug')
                                ->required()
                                ->readOnly(),

                            RichEditor::make('content')
                                ->label('Isi Berita')
                                ->required()
                                ->columnSpanFull(),
                        ]),

                    Section::make('Meta')
                        ->columnSpan(1)
                        ->schema([
                            FileUpload::make('thumbnail')
                                ->image()
                                ->disk('public')
                                ->visibility('public')
                                ->directory('posts'),

                            Select::make('category')
                                ->options([
                                    'news' => 'Berita',
                                    'activity' => 'Kegiatan',
                                    'announcement' => 'Pengumuman',
                                ])
                                ->required()
                                ->default('news'),

                            Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Tayang (Published)',
                                ])
                                ->required()
                                ->default('draft'),

                            Hidden::make('created_by')
                                ->default(fn () => auth()->id()),
                        ]),
                ])->columnSpanFull()
                    
            ]);
    }
}
