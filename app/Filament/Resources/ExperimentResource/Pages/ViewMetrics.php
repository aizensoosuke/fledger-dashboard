<?php

namespace App\Filament\Resources\ExperimentResource\Pages;

use App\Filament\Actions\ToLatestExperiment;
use App\Filament\Resources\ExperimentResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;

class ViewMetrics extends ViewRecord
{
    protected static string $resource = ExperimentResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $title = 'Metrics';

    public function getHeaderWidgetsColumns(): int|string|array
    {
        return 4;
    }

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    protected function getHeaderActions(): array
    {
        return [
            ToLatestExperiment::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ExperimentResource\Widgets\SuccessVTimeoutChart::make(),
            ExperimentResource\Widgets\SuccessChart::make(),
            ExperimentResource\Widgets\PagesPropagationChart::make(),
            ExperimentResource\Widgets\TimelessSeriesChart::make([
                'timelessSeriesName' => 'target_page_stored_bool',
            ]),
        ];
    }
}
