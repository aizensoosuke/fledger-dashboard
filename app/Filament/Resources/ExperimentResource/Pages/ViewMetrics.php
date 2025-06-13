<?php

namespace App\Filament\Resources\ExperimentResource\Pages;

use App\Filament\Actions\ToLatestExperiment;
use App\Filament\Resources\ExperimentResource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class ViewMetrics extends EditRecord
{
    protected static string $resource = ExperimentResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $title = 'Metrics';

    public function getFooterWidgetsColumns(): int|string|array
    {
        return 4;
    }

    public function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('target_page_id')
                ->label('Target Page ID')
                ->placeholder(fn ($state) => $state->record->target_page_id ?? 'Waiting... Please refresh manually')
                ->disabled(),
            TextInput::make('summary'),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            ToLatestExperiment::make(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            ExperimentResource\Widgets\SuccessVTimeoutChart::make(),
            ExperimentResource\Widgets\SuccessChart::make(),
            ExperimentResource\Widgets\FetchSuccessRateAmongBenevolent::make(),
            ExperimentResource\Widgets\FillersPropagationChart::make(),
            ExperimentResource\Widgets\TargetPropagationChart::make(),
            ExperimentResource\Widgets\TimelessSeriesChart::make([
                'timelessSeriesName' => 'target_page_successfully_stored_total',
            ]),

            ExperimentResource\Widgets\PagesPropagationChart::make(),
            ExperimentResource\Widgets\TimelessSeriesChart::make([
                'timelessSeriesName' => 'target_page_stored_bool',
            ]),
        ];
    }
}
