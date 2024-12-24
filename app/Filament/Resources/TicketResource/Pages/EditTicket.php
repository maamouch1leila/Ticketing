<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Models\User;
use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\TicketResource;
use Filament\Notifications\Actions\Action;


class EditTicket extends EditRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        $auth = auth()->user();
        $currentUser = User::find($auth->id);
        if ($record->technicien_id == NULL)
            return $record;

        if ($currentUser->isSuperAdmin()) {
            $recipient = User::find($record->technicien_id);
            Notification::make()
                ->icon('heroicon-o-ticket')
                ->color('info')
                ->iconColor('success')
                ->actions([
                    Action::make('edit')
                        ->url($this->getRedirectUrl()),
                    Action::make('markAsUnread')
                        ->markAsUnread()
                ])
                ->title('A new ticket has been affected to you')
                ->sendToDatabase($recipient);
        }

        return $record;
    }

    protected function authorizeAccess(): void
    {
        $user = User::find(auth()->id());
        if($user->checkPermissionTo('Traiter ticket'))
        {
            abort_unless($this->getRecord()->technicien_id == $user->id, 403);
        }
        abort_unless(static::getResource()::canView($this->getRecord()), 403);
    }
}
