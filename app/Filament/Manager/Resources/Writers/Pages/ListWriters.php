<?php

namespace App\Filament\Manager\Resources\Writers\Pages;

use App\Filament\Manager\Resources\Writers\WriterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWriters extends ListRecords
{
    protected static string $resource = WriterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
