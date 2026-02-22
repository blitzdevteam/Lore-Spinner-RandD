<?php

namespace App\Filament\Creator\Resources\Stories\Resources\Chapters\Pages;

use App\Enums\Chapter\ChapterStatusEnum;
use App\Filament\Creator\Resources\Stories\Resources\Chapters\ChapterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChapter extends CreateRecord
{
    protected static string $resource = ChapterResource::class;

    #[\Override]
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = ChapterStatusEnum::AWAITING_MANAGER_REVIEW;

        return parent::mutateFormDataBeforeCreate($data);
    }
}
