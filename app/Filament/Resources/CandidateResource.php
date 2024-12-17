<?php

namespace App\Filament\Resources;

use App\Enums\Association;
use App\Enums\CandidateStatus;
use App\Filament\Resources\CandidateResource\Pages;
use App\Filament\Resources\CandidateResource\RelationManagers;
use App\Models\Candidate;
use App\Models\Ileva\ConsultantTeamIleva;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CandidateResource extends Resource
{
    protected static ?string $model = Candidate::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $modelLabel = 'Candidatos';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Informações da Associação')
                    ->schema([
                        Select::make('association')
                            ->label('Associação')
                            ->options(Association::class)
                            ->live()
                            ->required(),
                        Select::make('ileva_team_id')
                            ->label('Equipe')
                            ->disabled(fn(Get $get) => empty($get('association')))
                            ->searchable()
                            ->getSearchResultsUsing(
                                function (string $search, Get $get): array {
                                    $databaseConnection = Association::from($get('association'))->getDatabaseConnection();

                                    return ConsultantTeamIleva::on($databaseConnection)
                                        ->where('equipe', 'like', "%{$search}%")
                                        ->where('stats', 1)
                                        ->limit(50)
                                        ->pluck('equipe', 'id')
                                        ->toArray();
                                }
                            )
                            ->getOptionLabelUsing(
                                function ($value, Get $get): ?string {

                                    if (empty($value)) {
                                        return null;
                                    }

                                    $databaseConnection = Association::from($get('association'))->getDatabaseConnection();

                                    return ConsultantTeamIleva::on($databaseConnection)
                                        ->find($value)
                                        ->equipe;
                                }
                            )
                            ->required(),
                    ]),
                Select::make('status')
                    ->label('Status')
                    ->options(CandidateStatus::class)
                    ->visible(fn($record) => $record->status != CandidateStatus::ACCEPTED)
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cpf')
                    ->label('CPF')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Telefone')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label('E-mail')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('progress_count')
                    ->counts([
                        'progress' => fn(Builder $query) => $query->where('is_completed', true),
                    ])
                    ->label('Aulas Assistidas')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->searchable()
                    ->sortable()
                    ->badge(),

            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
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
            'index' => Pages\ListCandidates::route('/'),
            'create' => Pages\CreateCandidate::route('/create'),
            'edit' => Pages\EditCandidate::route('/{record}/edit'),
        ];
    }
}
