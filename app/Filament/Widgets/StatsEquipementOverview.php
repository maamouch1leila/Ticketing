<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsEquipementOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected function getStats(): array
    {
        return [
            Stat::make('Materiel', Ticket::query()->where('type_probleme', 'Materiel')->count())
                ->extraAttributes([
                    'class' => 'flex  items-center h-16 p-4',
                ]),
            Stat::make('Reseaux', Ticket::query()->where('type_probleme', 'Reseaux')->count())
            ->extraAttributes([
                'class' => 'flex justify-start items-center h-16 p-4',
            ]),
            Stat::make('Application', Ticket::query()->where('type_probleme', 'Application')->count())
            ->extraAttributes([
                'class' => 'flex justify-start items-center h-16 p-4',
            ]),
            Stat::make('Systeme d\'exploitation', Ticket::query()->where('type_probleme', 'Systeme_d_exploitation')->count())
            ->extraAttributes([
                'class' => 'flex justify-start items-center h-16 p-4',
            ]),
        ];
    }
}
