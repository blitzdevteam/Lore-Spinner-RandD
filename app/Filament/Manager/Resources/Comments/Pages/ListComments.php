<?php

namespace App\Filament\Manager\Resources\Comments\Pages;

use App\Filament\Manager\Resources\Comments\CommentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListComments extends ListRecords
{
    protected static string $resource = CommentResource::class;
}
