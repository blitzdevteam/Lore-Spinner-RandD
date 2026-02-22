<?php

declare(strict_types=1);

namespace App\Filament\Creator\Resources\Stories\Pages;

use App\Filament\Creator\Resources\Stories\StoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewStory extends ViewRecord
{
    protected static string $resource = StoryResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
