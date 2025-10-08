<?php

namespace App\Filament\Manager\Resources\Stories\Pages;

use App\Filament\Manager\Resources\Stories\StoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStory extends ViewRecord
{
    protected static string $resource = StoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
