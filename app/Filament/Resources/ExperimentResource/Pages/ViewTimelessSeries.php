<?php

namespace App\Filament\Resources\ExperimentResource\Pages;

use App\Filament\Resources\ExperimentResource;
use App\Models\DataPoint;
use App\Models\Experiment;
use App\Models\TimelessDataPoint;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;

class ViewTimelessSeries extends ViewRecord
{
    protected static string $resource = ExperimentResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $title = 'Timeless Series';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public function getHeaderWidgetsColumns(): int|string|array
    {
        return 4;
    }

    protected function getHeaderWidgets(): array
    {
        $timelessSeriesName = TimelessDataPoint::whereIn('node_id', $this->record->nodes->pluck('id'))
            ->get()
            ->pluck('name')
            ->unique();

        return $timelessSeriesName->map(function ($name) {
            return ExperimentResource\Widgets\TimelessSeriesChart::make([
                'timelessSeriesName' => $name,
            ]);
        })->toArray();
    }
}
