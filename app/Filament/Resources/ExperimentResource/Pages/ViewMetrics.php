<?php

namespace App\Filament\Resources\ExperimentResource\Pages;

use App\Filament\Resources\ExperimentResource;
use Filament\Actions\Action;
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
            Action::make('To lastest experiment')
                ->outlined()
                ->action(function () {
                    $url = ExperimentResource::getUrl('metrics', [
                        'record' => $this->record->latestExperiment(),
                    ]);
                    $this->redirect($url);
                })
                ->icon('heroicon-o-arrow-right'),
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
