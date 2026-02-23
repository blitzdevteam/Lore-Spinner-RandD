<?php

namespace App\Filament\Creator\Resources\Stories\Resources\Chapters\Pages;

use App\Enums\Chapter\ChapterStatusEnum;
use App\Filament\Creator\Resources\Stories\Resources\Chapters\ChapterResource;
use App\Models\Chapter;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditChapter extends EditRecord
{
    protected static string $resource = ChapterResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    #[\Override]
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['status'] = ChapterStatusEnum::AWAITING_CREATOR_REVIEW;

        return parent::mutateFormDataBeforeSave($data);
    }

    protected function afterSave(): void
    {
        /** @var Chapter $chapter */
        $chapter = $this->record;

        $chapter->events()->delete();
    }
}
