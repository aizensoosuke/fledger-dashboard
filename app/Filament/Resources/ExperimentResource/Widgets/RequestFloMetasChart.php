<?php

namespace App\Filament\Resources\ExperimentResource\Widgets;

use App\Models\Experiment;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\WidgetConfiguration;

class RequestFloMetasChart extends ChartWidget
{
    protected static ?string $heading = '# RequestFloMetas receive';

    public ?Experiment $record = null;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $experiment = $this->record;

        return [
            'datasets' => [
                [
                    'label' => $this->getHeading(),
                    'data' => $experiment->nodes->map(fn ($node) => $node->amount_request_flo_metas_received),
                ],
            ],
            'labels' => $experiment->nodes->map(fn ($node) => "{$node->name}"),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
