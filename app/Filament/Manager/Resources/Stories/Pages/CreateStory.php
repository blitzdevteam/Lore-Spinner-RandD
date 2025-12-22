<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Stories\Pages;

use App\Filament\Manager\Resources\Stories\StoryResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateStory extends CreateRecord
{
    protected static string $resource = StoryResource::class;

    protected static bool $canCreateAnother = false;
}
