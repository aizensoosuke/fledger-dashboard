<?php

namespace App\Filament\Resources\ExperimentResource\Pages;

use App\Filament\Resources\ExperimentResource;
use App\Models\DataPoint;
use App\Models\Experiment;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;

class ViewTimeSeries extends ViewRecord
{
    protected static string $resource = ExperimentResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';

    protected static ?string $title = 'Time Series';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    protected function getHeaderWidgets(): array
    {
        $timeSeriesNames = DataPoint::whereIn('node_id', $this->record->nodes->pluck('id'))
            ->get()
            ->pluck('name')
            ->unique();

        return $timeSeriesNames->map(function ($name) {
            return ExperimentResource\Widgets\TimeSeriesChart::make([
                'timeSeriesName' => $name,
            ]);
        })->toArray();
    }
}
