<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Comments\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

final class CommentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Textarea::make('content')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
