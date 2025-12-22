<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Comments\Pages;

use App\Enums\Comment\StatusEnum;
use App\Filament\Manager\Resources\Comments\CommentResource;
use App\Models\Comment;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

final class ViewComment extends ViewRecord
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        /**
         * @var Comment $record
         */
        $record = $this->record;

        return [
            EditAction::make()
                ->visible($record->status === StatusEnum::PENDING),
            DeleteAction::make()
                ->visible($record->status === StatusEnum::PENDING),
        ];
    }
}
