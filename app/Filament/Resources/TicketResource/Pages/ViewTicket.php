<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Models\User;
use Filament\Actions;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\TicketResource;

class ViewTicket extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('exportPdf')
                ->label('Download PDF')
                ->action(fn () => self::exportPdf($this->getRecord()))
                ->icon('heroicon-o-document'),
        ];
    }

    public static function exportPDF($record)
    {
        $pdf = Pdf::loadView('pdf.ticket', ['record' => $record]);

        return response()->streamDownload(
            fn () => print ($pdf->output()),
            "ticket-{$record->N_ticket}.pdf"
        );
    }

    protected function authorizeAccess(): void
    {
        $user = User::find(auth()->id());
        if ($user->checkPermissionTo('Traiter ticket')) {
            abort_unless($this->getRecord()->technicien_id == $user->id, 403);
        }
        abort_unless(static::getResource()::canView($this->getRecord()), 403);
    }
}
