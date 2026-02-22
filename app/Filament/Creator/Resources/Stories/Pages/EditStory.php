<?php

declare(strict_types=1);

namespace App\Filament\Creator\Resources\Stories\Pages;

use App\Filament\Creator\Resources\Stories\StoryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

final class EditStory extends EditRecord
{
    protected static string $resource = StoryResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
