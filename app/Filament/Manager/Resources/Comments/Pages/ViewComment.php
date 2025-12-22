<?php

declare(strict_types=1);

namespace App\Filament\Manager\Resources\Comments\Pages;

use App\Enums\Comment\CommentStatusEnum;
use App\Filament\Manager\Resources\Comments\CommentResource;
use App\Models\Comment;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
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
            Action::make('approve')
                ->action(function (array $data, Comment $record): void {
                    $record->update([
                        'status' => CommentStatusEnum::APPROVED,
                        'approved_at' => now(),
                        'content' => $data['content'],
                    ]);
                })
                ->color('success')
                ->requiresConfirmation()
                ->schema([
                    Textarea::make('content')
                        ->label('Approved Comment Content')
                        ->default($record->content)
                        ->rows(4)
                        ->required()
                        ->helperText('You can modify the comment content before approving.')
                ])
                ->modal()
                ->modalWidth('2xl')
                ->visible(fn (): bool => $record->status === CommentStatusEnum::PENDING),
            Action::make('decline')
                ->color('danger')
                ->action(function () use ($record): void {
                    $record->update([
                        'status' => CommentStatusEnum::DECLINED,
                    ]);
                    $this->redirect(ViewComment::getUrl(['record' => $record->id]));
                })
        ];
    }
}
