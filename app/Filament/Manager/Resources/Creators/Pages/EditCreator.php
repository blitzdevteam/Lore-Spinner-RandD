<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Creators\Pages;

use App\Filament\Manager\Resources\Creators\CreatorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

final class EditCreator extends EditRecord
{
    protected static string $resource = CreatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
