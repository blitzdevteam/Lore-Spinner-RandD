<?php

namespace App\Filament\Creator\Resources\Stories\Resources\Chapters\Pages;

use App\Enums\Chapter\ChapterStatusEnum;
use App\Filament\Creator\Resources\Stories\Resources\Chapters\ChapterResource;
use App\Jobs\Event\EventExtractorJob;
use App\Models\Chapter;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewChapter extends ViewRecord
{
    protected static string $resource = ChapterResource::class;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('mark-as-approve')
                ->label('Approve')
                ->action(function (Chapter $chapter): void {
                    $chapter->update([
                        'status' => ChapterStatusEnum::AWAITING_EXTRACTING_EVENTS_REQUEST,
                    ]);

                    EventExtractorJob::dispatch($chapter);
                })
                ->color('info')
                ->requiresConfirmation()
                ->modal()
                ->visible(fn (Chapter $chapter): bool => $chapter->status === ChapterStatusEnum::AWAITING_CREATOR_REVIEW),

            EditAction::make()
                ->hidden(fn (Chapter $chapter): bool => $chapter->status === ChapterStatusEnum::EXTRACTING_EVENTS),
        ];
    }
}
