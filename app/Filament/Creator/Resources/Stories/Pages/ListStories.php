<?php

declare(strict_types=1);

namespace App\Filament\Creator\Resources\Stories\Pages;

use App\Filament\Creator\Resources\Stories\StoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListStories extends ListRecords
{
    protected static string $resource = StoryResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
