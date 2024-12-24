<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use App\Filament\Resources\TicketResource;
use Filament\Resources\Pages\CreateRecord;

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
      

        $superadmins = User::all()->filter(function (User $user){
            if($user->isSuperAdmin()) {
               return $user;
            }
         
        });
        Notification::make()
            ->title('New Ticket has been created')
            ->sendToDatabase($superadmins);
    }
}
