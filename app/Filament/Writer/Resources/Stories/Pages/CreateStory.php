<?php

declare(strict_types=1);

namespace App\Filament\Writer\Resources\Stories\Pages;

use App\Enums\Story\StoryStatusEnum;
use App\Filament\Writer\Resources\Stories\StoryResource;
use App\Jobs\Chapter\ChapterExtractorJob;
use App\Models\Story;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

final class CreateStory extends CreateRecord
{
    protected static string $resource = StoryResource::class;

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['writer_id'] = auth()->id();

        return parent::mutateFormDataBeforeCreate($data);
    }

    protected function handleRecordCreation(array $data): Model
    {
        /** @var Story $story */
        $story = parent::handleRecordCreation($data);

        if ($data['use_script_upload']) {
            $story->update([
                'status' => StoryStatusEnum::AWAITING_EXTRACTING_CHAPTERS_REQUEST,
            ]);

            ChapterExtractorJob::dispatch($story);
        } else {
            $story->update([
                'status' => StoryStatusEnum::DRAFT,
            ]);
        }

        return $story;
    }
}
