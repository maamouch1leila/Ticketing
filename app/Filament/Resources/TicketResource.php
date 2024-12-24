<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Resources\TicketResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('probleme_declare')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('commentaires')
                            ->required(),
                        Forms\Components\Select::make('priorite')
                            ->options([
                                'Faible' => 'Faible',
                                'Moyenne' => 'Moyenne',
                                'Eleve' => 'Elevé',
                                'Urgent' => 'Urgent',
                            ])
                            ->required(),
                        Forms\Components\Select::make('type_probleme')
                            ->options([
                                'Materiel' => 'Matériel',
                                'Reseaux' => 'Réseaux',
                                'Application' => 'Application',
                                'Systeme_d_exploitation' => 'Système d\'exploitation',
                            ])
                            ->nullable()
                            ->label('Type de problème')
                            ->reactive(),
                        Forms\Components\Select::make('type_materiel')
                            ->options([
                                'Imprimante' => 'Imprimante',
                                'Ordinateur' => 'Ordinateur',
                                'Scanner' => 'Scanner',
                                'Autre' => 'Autre',
                            ])
                            ->label('Type de materiel')
                            ->required()
                            ->visible(function (callable $get) {
                                if ($get('type_probleme') == 'Materiel')
                                    return true;
                                else return false;
                            })
                            ->reactive(),
                        Forms\Components\TextInput::make('marque')
                            ->label('La marque du materiel')
                            ->visible(function (callable $get) {
                                if ($get('type_materiel')) return true;
                                return false;
                            })
                            ->required(),
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Screenshot')
                            ->imagePreviewHeight(200)
                            ->image(),
                    ])->columns(3),
                Forms\Components\Section::make('affectation de technicien')
                    ->visible(function () {
                        $auth = User::find(auth()->user()->id);
                        if ($auth->isSuperAdmin()) return true;
                        return false;
                    })
                    ->schema([
                        Forms\Components\Select::make('technicien_id')
                            ->relationship('technicien', 'name')

                            ->getOptionLabelFromRecordUsing(fn (Model $record) => "{$record->name}, {$record->email}, Travaille dans {$record->affectation}")
                            ->searchable(['name', 'email', 'affectation'])
                            ->preload()
                    ]),
                Forms\Components\Section::make()

                    ->visible(function () {
                        $auth = User::find(auth()->user()->id);
                        if ($auth->checkPermissionTo('traiter ticket')) return true;
                        return false;
                    })

                    ->schema([

                        Forms\Components\DatePicker::make('date_de_qualification'),
                        Forms\Components\TextInput::make('qualifie_par')
                            ->maxLength(255)
                            ->default(null),



                        Forms\Components\TextInput::make('probleme_rencontre')
                            ->maxLength(255)
                            ->default(null),
                    ]),
                Forms\Components\Section::make()

                    ->visible(function () {
                        $auth = User::find(auth()->user()->id);
                        if ($auth->checkPermissionTo('traiter ticket')) return true;
                        return false;
                    })

                    ->schema([
                        Forms\Components\DatePicker::make('date_de_reparation'),
                        Forms\Components\TextInput::make('repare_par')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\TextInput::make('lieu_de_reparation')
                            ->maxLength(255)
                            ->default(null),
                        Forms\Components\DatePicker::make('date_de_cloture'),
                        Forms\Components\TextInput::make('travaux_effectues')
                            ->maxLength(255)
                            ->default(null),
                        ToggleButtons::make('status')
                            ->hiddenOn('create')
                            ->options(
                                [
                                    'Ouvert' => 'Ouvert',
                                    'Qualifie' => 'Qualifie',
                                    'Repare' => 'Repare',
                                    'Cloture' => 'Cloture'
                                ]
                            )
                            ->icons([
                                'Ouvert' => 'heroicon-o-pencil',
                                'Qualifie' => 'heroicon-o-clock',
                                'Repare' => 'heroicon-o-clock',
                                'Cloture' => 'heroicon-o-check-circle'


                            ])
                            ->colors([
                                'Ouvert' => 'primary',
                                'Qualifie' => 'warning',
                                'Repare' => 'success',
                                'Cloture' => 'info'
                            ])
                            ->in(fn (ToggleButtons $component): array => array_keys($component->getEnabledOptions()))
                            ->inline(),
                    ]),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                $auth = auth()->user();
                $user = User::find($auth->id);
                if ($user->hasRole('technicien')) {
                    return $query->where('technicien_id', $auth->id)->orderBy('created_at', 'DESC');
                } else if ($user->hasRole('Employee')) {
                    return $query->where('user_id', $auth->id)->orderBy('created_at', 'DESC');
                } else if ($user->isSuperAdmin()) {
                    return $query->orderBy('created_at', 'DESC');
                }
            })
            ->columns([
                Tables\Columns\TextColumn::make('N_ticket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('probleme_declare')
                    ->searchable(),
                Tables\Columns\TextColumn::make('commentaires')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_de_qualification')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('qualifie_par')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type_probleme'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Ouvert' => 'gray',
                        'Qualifie' => 'warning',
                        'Repare' => 'success',
                        'Cloture' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('priorite')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'Faible' => 'info',
                    'Moyenne' => 'primary',
                    'Eleve' => 'warning',
                    'Urgent' => 'danger',
                }),
                Tables\Columns\TextColumn::make('probleme_rencontre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_de_reparation')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('repare_par')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lieu_de_reparation')
                    ->searchable(),
                Tables\Columns\TextColumn::make('date_de_cloture')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('travaux_effectues')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_path'),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('technicien.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'view' => Pages\ViewTicket::route('/{record}'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
