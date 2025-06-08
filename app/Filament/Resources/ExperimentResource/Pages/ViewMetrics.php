<?php

namespace App\Filament\Resources\ExperimentResource\Pages;

use App\Filament\Resources\ExperimentResource;
use App\Models\Experiment;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\ViewRecord;

class ViewMetrics extends ViewRecord
{
    protected static string $resource = ExperimentResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';

    protected static ?string $title = 'Metrics';

    public function form(Form $form): Form
    {
        return $form->schema([]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ExperimentResource\Widgets\PagesPropagationChart::make(),
            ExperimentResource\Widgets\RequestFloMetasChart::make(),
            ExperimentResource\Widgets\FloValueSentChart::make(),
        ];
    }
}
