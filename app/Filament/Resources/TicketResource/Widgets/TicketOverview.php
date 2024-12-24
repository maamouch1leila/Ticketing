<?php

namespace App\Filament\Resources\TicketResource\Widgets;

use App\Models\User;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class TicketOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected function getStats(): array
    {
        $user = User::find(auth()->id());
        if($user->isSuperAdmin())
        {
            return [
                Stat::make('Tickets ouverts', Ticket::query()->where('status','Ouvert')->count()),
                Stat::make('Tickets qualifié',Ticket::query()->where('status','Qualifie')->count()),
                Stat::make('Tickets reparé', Ticket::query()->where('status','Repare')->count()),
                Stat::make('Tickets cloturé', Ticket::query()->where('status','Cloture')->count()),
            ];
        }else if($user->checkPermissionTo('Traiter ticket'))
        {
            return [
                Stat::make('Tickets ouverts', Ticket::query()->where('technicien_id',$user->id)->where('status','Ouvert')->count()),
                Stat::make('Tickets qualifié',Ticket::query()->where('technicien_id',$user->id)->where('status','Qualifie')->count()),
                Stat::make('Tickets reparé', Ticket::query()->where('technicien_id',$user->id)->where('status','Repare')->count()),
                Stat::make('Tickets cloturé', Ticket::query()->where('technicien_id',$user->id)->where('status','Cloture')->count()),
            ];
        }else{
            return [
                Stat::make('Tickets ouverts', Ticket::query()->where('user_id',$user->id)->where('status','Ouvert')->count()),
                Stat::make('Tickets qualifié',Ticket::query()->where('user_id',$user->id)->where('status','Qualifie')->count()),
                Stat::make('Tickets reparé', Ticket::query()->where('user_id',$user->id)->where('status','Repare')->count()),
                Stat::make('Tickets cloturé', Ticket::query()->where('user_id',$user->id)->where('status','Cloture')->count()),
            ];
        }
       
    }
}
