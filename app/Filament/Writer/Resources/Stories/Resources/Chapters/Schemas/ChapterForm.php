<?php

namespace App\Filament\Writer\Resources\Stories\Resources\Chapters\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ChapterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->columnSpanFull()
                    ->columns(2)
                    ->heading('Chapter details')
                    ->schema([
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(2),
                        Textarea::make('teaser')
                            ->required()
                            ->helperText('Provide a brief teaser of this chapter.')
                            ->rows(3)
                            ->columnSpan(2),
                        RichEditor::make('content')
                            ->required()
                            ->helperText('Write the full content of this chapter.')
                            ->toolbarButtons([
                                'redo',
                                'undo',
                            ])
                            ->columnSpan(2),
                    ]),
            ]);
    }
}
