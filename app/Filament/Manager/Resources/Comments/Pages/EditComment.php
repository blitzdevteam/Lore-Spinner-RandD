<?php

namespace App\Filament\Manager\Resources\Comments\Pages;

use App\Enums\Comment\StatusEnum;
use App\Filament\Manager\Resources\Comments\CommentResource;
use App\Models\Comment;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditComment extends EditRecord
{
    protected static string $resource = CommentResource::class;

    #[\Override]
    public function mount(int|string $record): void
    {
        parent::mount($record);

        /**
         * @var Comment $record
         */
        $record = $this->record;

        if ($record->status !== StatusEnum::PENDING) {
            $this->redirect(ViewComment::getUrl(['record' => $this->record->id]));
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    #[\Override]
    protected function getFormActions(): array
    {
        /**
         * @var Comment $record
         */
        $record = $this->record;
        return [
            Action::make('approve')
                ->color('success')
                ->action(function () use ($record): void {
                    $record->update([
                        'status' => StatusEnum::APPROVED,
                        'approved_at' => now(),
                    ]);
                    $this->redirect(ViewComment::getUrl(['record' => $record->id]));
                })
                ->visible(fn (): bool => $record->status === StatusEnum::PENDING),
            Action::make('decline')
                ->color('danger')
                ->action(function () use ($record): void {
                    $record->update([
                        'status' => StatusEnum::DECLINED,
                    ]);
                    $this->redirect(ViewComment::getUrl(['record' => $record->id]));
                })
                ->visible(fn (): bool => $record->status === StatusEnum::PENDING),
            $this->getCancelFormAction(),
        ];
    }
}
