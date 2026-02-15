<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Creators\Pages;

use App\Filament\Manager\Resources\Creators\CreatorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListCreators extends ListRecords
{
    protected static string $resource = CreatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
