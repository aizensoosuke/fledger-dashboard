<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperimentResource\Pages;
use App\Filament\Resources\ExperimentResource\Widgets\FloValueSentChart;
use App\Filament\Resources\ExperimentResource\Widgets\PagesPropagationChart;
use App\Filament\Resources\ExperimentResource\Widgets\RequestFloMetasChart;
use App\Models\Experiment;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExperimentResource extends Resource
{
    protected static ?string $model = Experiment::class;

    protected static ?string $slug = 'experiments';

    protected static ?string $navigationIcon = 'heroicon-o-rocket-launch';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\EditExperiment::class,
            Pages\ViewMetrics::class,
            Pages\ManageNodes::class,
        ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id')
                    ->label('Experiment ID')
                    ->disabled(),
                TextInput::make('name')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                    //->getStateUsing(fn (Experiment $experiment) => "Experiment #{$experiment->id}");

                TextColumn::make('name'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                EditAction::make()
                    ->url(fn ($record) => static::getUrl('metrics', ['record' => $record])),
                DeleteAction::make(),
                RestoreAction::make(),
                ForceDeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExperiments::route('/'),
            'create' => Pages\CreateExperiment::route('/create'),
            'edit' => Pages\EditExperiment::route('/{record}/edit'),

            'metrics' => Pages\ViewMetrics::route('/{record}/metrics'),
            'nodes' => Pages\ManageNodes::route('/{record}/nodes'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getGloballySearchableAttributes(): array
    {
        return [];
    }

    public static function getWidgets(): array
    {
        return [
            PagesPropagationChart::class,
            RequestFloMetasChart::class,
            FloValueSentChart::class,
        ];
    }
}
