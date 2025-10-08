<?php

namespace App\Filament\Manager\Resources\Writers\Pages;

use App\Filament\Manager\Resources\Writers\WriterResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWriter extends EditRecord
{
    protected static string $resource = WriterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
