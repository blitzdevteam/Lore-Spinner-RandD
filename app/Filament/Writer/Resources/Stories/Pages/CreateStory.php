<?php

declare(strict_types=1);

namespace App\Filament\Writer\Resources\Stories\Pages;

use App\Enums\Story\StoryStatusEnum;
use App\Filament\Writer\Resources\Stories\StoryResource;
use App\Jobs\Story\StoryChapterExtractionJob;
use App\Models\Story;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

final class CreateStory extends CreateRecord
{
    protected static string $resource = StoryResource::class;

    protected static bool $canCreateAnother = false;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var Story $story */
        $story = parent::handleRecordCreation($data);

        if ($data['use_script_upload']) {
            $story->update([
                'status' => StoryStatusEnum::AWAITING_EXTRACTING_CHAPTERS_REQUEST,
            ]);

            StoryChapterExtractionJob::dispatch($story);
        }

        return $story;
    }
}
