<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Creators\Pages;

use App\Filament\Manager\Resources\Creators\CreatorResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateCreator extends CreateRecord
{
    protected static string $resource = CreatorResource::class;

    protected static bool $canCreateAnother = false;
}
