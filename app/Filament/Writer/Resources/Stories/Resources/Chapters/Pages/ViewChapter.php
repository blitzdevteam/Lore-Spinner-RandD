<?php

namespace App\Filament\Writer\Resources\Stories\Resources\Chapters\Pages;

use App\Filament\Writer\Resources\Stories\Resources\Chapters\ChapterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewChapter extends ViewRecord
{
    protected static string $resource = ChapterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
