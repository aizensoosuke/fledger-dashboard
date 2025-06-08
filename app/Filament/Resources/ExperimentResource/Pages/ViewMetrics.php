<?php

namespace App\Filament\Resources\ExperimentResource\Pages;

use App\Filament\Resources\ExperimentResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;

class ViewMetrics extends ViewRecord
{
    protected static string $resource = ExperimentResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $title = 'Metrics';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ExperimentResource\Widgets\SuccessVTimeoutChart::make(),
            ExperimentResource\Widgets\PagesPropagationChart::make(),
        ];
    }
}
