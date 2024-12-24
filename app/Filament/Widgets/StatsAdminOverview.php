<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsAdminOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';

    protected function getStats(): array
    {
        return [
            Stat::make('Tickets ouverts', Ticket::query()->where('status','Ouvert')->count()),
            Stat::make('Tickets qualifié',Ticket::query()->where('status','Qualifie')->count()),
            Stat::make('Tickets reparé', Ticket::query()->where('status','Repare')->count()),
            Stat::make('Tickets cloturé', Ticket::query()->where('status','Cloture')->count()),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            StatsEquipementOverview::class,
        ];
    }
}

