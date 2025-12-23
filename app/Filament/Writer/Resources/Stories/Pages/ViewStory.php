<?php

declare(strict_types=1);

namespace App\Filament\Writer\Resources\Stories\Pages;

use App\Filament\Writer\Resources\Stories\StoryResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewStory extends ViewRecord
{
    protected static string $resource = StoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
