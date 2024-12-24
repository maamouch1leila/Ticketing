<?php

namespace App\Filament\Resources\TicketResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TicketResource;
use App\Models\User;
use Filament\Resources\Components\Tab;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            TicketResource\Widgets\TicketOverview::class,
        ];
    }
    public function getTabs(): array
    {
        $user = User::find(auth()->id());
        if($user->isSuperAdmin())
        {
            return [
                'all' => Tab::make(('all')),
                'ouvert' => Tab::make(('Ouvert'))
                    ->modifyQueryUsing(function($query){
                        $query->where('status','Ouvert');
                    })->icon('heroicon-o-check'),
               'reparation' => Tab::make(('Qualifie'))
                    ->modifyQueryUsing(function($query){
                        $query->where('status','Qualifie');
                    })->icon('heroicon-o-clock'),
                'qualifie' => Tab::make(('Repare'))
                    ->modifyQueryUsing(function($query){
                        $query->where('status','Repare');
                    })->icon('heroicon-o-clock'),
               'repare' => Tab::make(('Cloture'))
                    ->modifyQueryUsing(function($query){
                        $query->where('status','Cloture');
                    })->icon('heroicon-o-check-circle'),
                'non-affecte' => Tab::make(('Non affectés'))
                    
                    ->modifyQueryUsing(function($query){
                        $query->where('technicien_id',null);
                    })
                ];
        }
        return [
            'all' => Tab::make(('all')),
            'ouvert' => Tab::make(('Ouvert'))
                ->modifyQueryUsing(function($query){
                    $query->where('status','Ouvert');
                })->icon('heroicon-o-check'),
           'reparation' => Tab::make(('Qualifie'))
                ->modifyQueryUsing(function($query){
                    $query->where('status','Qualifie');
                })->icon('heroicon-o-clock'),
            'qualifie' => Tab::make(('Repare'))
                ->modifyQueryUsing(function($query){
                    $query->where('status','Repare');
                })->icon('heroicon-o-clock'),
           'repare' => Tab::make(('Cloture'))
                ->modifyQueryUsing(function($query){
                    $query->where('status','Cloture');
                })->icon('heroicon-o-check-circle'),
           
        ];
    }
    
}
