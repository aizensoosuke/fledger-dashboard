<?php

namespace App\Filament\Resources\ExperimentResource\Pages;

use App\Filament\Resources\ExperimentResource;
use App\Filament\Traits\HasResourceTitle;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageNodes extends ManageRelatedRecords
{
    use HasResourceTitle;

    protected static string $resource = ExperimentResource::class;

    protected static string $relationship = 'nodes';

    protected static ?string $navigationIcon = 'heroicon-o-cube-transparent';

    protected static ?string $title = 'Nodes';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
