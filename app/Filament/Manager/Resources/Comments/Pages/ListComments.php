<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Comments\Pages;

use App\Filament\Manager\Resources\Comments\CommentResource;
use Filament\Resources\Pages\ListRecords;

final class ListComments extends ListRecords
{
    protected static string $resource = CommentResource::class;
}
