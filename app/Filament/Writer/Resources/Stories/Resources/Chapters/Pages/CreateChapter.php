<?php

namespace App\Filament\Writer\Resources\Stories\Resources\Chapters\Pages;

use App\Filament\Writer\Resources\Stories\Resources\Chapters\ChapterResource;
use Filament\Resources\Pages\CreateRecord;

class CreateChapter extends CreateRecord
{
    protected static string $resource = ChapterResource::class;
}
