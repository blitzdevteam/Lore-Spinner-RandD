<?php

namespace App\Filament\Writer\Resources\Stories\Resources\Chapters\Pages;

use App\Enums\Chapter\ChapterStatusEnum;
use App\Filament\Writer\Resources\Stories\Resources\Chapters\ChapterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChapter extends CreateRecord
{
    protected static string $resource = ChapterResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = ChapterStatusEnum::AWAITING_MANAGER_REVIEW;

        return parent::mutateFormDataBeforeCreate($data);
    }
}
