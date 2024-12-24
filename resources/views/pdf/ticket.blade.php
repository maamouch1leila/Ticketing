<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details</title>
    <style>
        @page { margin: 20px; }
        body { font-family: sans-serif; }
        .header { text-align: center; font-size: 24px; font-weight: bold; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #f4f4f4; }
        .image { text-align: center; margin-top: 20px; }
        .status { margin-top: 20px; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">Ticket Details - {{ $record->N_ticket }}</div>
    <table class="table">
        <tr>
            <th>Probleme déclaré</th>
            <td>{{ $record->probleme_declare ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Commentaires</th>
            <td>{{ $record->commentaires ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Date de qualification</th>
            <td>{{ $record->date_de_qualification ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Qualifié par</th>
            <td>{{ $record->qualifie_par ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Type de problème</th>
            <td>{{ $record->type_probleme ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Type matériel</th>
            <td>{{ $record->type_materiel ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Marque</th>
            <td>{{ $record->marque ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Priorité</th>
            <td>{{ $record->priorite ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Problème rencontré</th>
            <td>{{ $record->probleme_rencontre ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Date de réparation</th>
            <td>{{ $record->date_de_reparation ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Réparé par</th>
            <td>{{ $record->repare_par ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Lieu de réparation</th>
            <td>{{ $record->lieu_de_reparation ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Date de clôture</th>
            <td>{{ $record->date_de_cloture ?? 'N/A' }}</td>
        </tr>
        <tr>
            <th>Travaux effectués</th>
            <td>{{ $record->travaux_effectues ?? 'N/A' }}</td>
        </tr>
    </table>
    <div class="status">
        Status: {{ $record->status ?? 'N/A' }}
    </div>
    <div class="image">
        @if ($record->image_path)
            <img src="{{ asset('storage/' . $record->image_path) }}" alt="Screenshot" style="max-width: 100%; height: auto;">
        @else
            <p>No Screenshot Available</p>
        @endif
    </div>
</body>
</html>
