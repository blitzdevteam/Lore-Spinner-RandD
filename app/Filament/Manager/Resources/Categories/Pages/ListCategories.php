<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Categories\Pages;

use App\Filament\Manager\Resources\Categories\CategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->modalWidth('md'),
        ];
    }
}
