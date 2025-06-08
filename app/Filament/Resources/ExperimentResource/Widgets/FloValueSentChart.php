<?php

namespace App\Filament\Resources\ExperimentResource\Widgets;

use App\Models\Experiment;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\WidgetConfiguration;

class FloValueSentChart extends ChartWidget
{
    protected static ?string $heading = '# FloValue sent';

    public ?Experiment $record = null;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $experiment = $this->record;

        $nodes = $experiment->nodes()->orderBy('name')->get();

        return [
            'datasets' => [
                [
                    'label' => $this->getHeading(),
                    'data' => $nodes->map(fn ($node) => $node->amount_flo_value_sent),
                ],
            ],
            'labels' => $nodes->map(fn ($node) => "{$node->name}"),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
