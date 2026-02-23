<?php

declare(strict_types=1);

namespace App\Filament\Creator\Resources\Stories\Pages;

use App\Enums\Story\StoryStatusEnum;
use App\Filament\Creator\Resources\Stories\StoryResource;
use App\Models\Story;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\RichEditor;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;
use Filament\Support\Colors\Color;

final class ViewStory extends ViewRecord
{
    protected static string $resource = StoryResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('mark-as-published')
                ->label('Mark as Published')
                ->modalWidth('3xl')
                ->modalDescription('This action will mark the story as published and make it visible to the public. This action cannot be undone.')
                ->action(function (array $data, Story $story): void {
                    $story->update([
                        'opening' => $data['opening'],
                        'status' => StoryStatusEnum::PUBLISHED,
                    ]);
                })
                ->color(Color::Green)
                ->icon(Heroicon::ExclamationTriangle)
                ->requiresConfirmation()
                ->schema([
                    RichEditor::make('opening')
                        ->required()
                        ->helperText('This will be used as the opening of the story when it is published')
                        ->toolbarButtons([
                            'redo', 'undo', 'underline', 'italic', 'bold',
                        ])
                        ->columnSpan(2),
                ])
                ->visible(fn (Story $story): bool => $story->canMarkAsPublished()),

            EditAction::make(),
        ];
    }
}
