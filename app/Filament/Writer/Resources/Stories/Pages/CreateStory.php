<?php

declare(strict_types=1);

namespace App\Filament\Writer\Resources\Stories\Pages;

use App\Filament\Writer\Resources\Stories\StoryResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateStory extends CreateRecord
{
    protected static string $resource = StoryResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return [
            ...$data,
            'writer_id' => auth('writer')->id(),
        ];
    }
}
