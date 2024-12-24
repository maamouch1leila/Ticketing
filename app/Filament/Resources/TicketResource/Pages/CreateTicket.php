<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Models\User;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateTicket extends CreateRecord
{
    protected static string $resource = TicketResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        return $data;
    }

    public function afterCreate(): void
    {

        $recipient = User::find($this->getRecord()->technicien_id);
        if($recipient)
        {
            Notification::make()
            ->icon('heroicon-o-ticket')
            ->color('info')
            ->iconColor('success')
            ->actions([
                Action::make('edit')
                    ->url($this->getRedirectUrl()),
                Action::make('markAsUnread')
                    ->markAsUnread(),
            ])
            ->title('A new ticket has been affected to you')
            ->sendToDatabase($recipient);
        }
        

        $superadmins = User::all()->filter(function (User $user) {
            if ($user->isSuperAdmin()) {
                return $user;
            }

        });
        Notification::make()
            ->title('New Ticket has been created')
            ->sendToDatabase($superadmins);
    }
}
