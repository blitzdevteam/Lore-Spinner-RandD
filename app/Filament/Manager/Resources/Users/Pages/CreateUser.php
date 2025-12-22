<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Users\Pages;

use App\Filament\Manager\Resources\Users\UserResource;
use Filament\Resources\Pages\CreateRecord;

final class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected static bool $canCreateAnother = false;
}
