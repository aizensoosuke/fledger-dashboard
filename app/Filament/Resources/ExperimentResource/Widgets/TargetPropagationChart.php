<?php

namespace App\Filament\Resources\ExperimentResource\Widgets;

use App\Models\Experiment;
use Filament\Widgets\ChartWidget;

class TargetPropagationChart extends ChartWidget
{
    protected static ?string $heading = 'Target Propagation';

    public ?Experiment $record = null;

    protected int|string|array $columnSpan = 2;

    protected function getData(): array
    {
        $experiment = $this->record;

        $pages = collect(range(1, $experiment->target_amount))->map(fn ($page) => "target-{$page}");
        $amounts = $pages->map(function ($page) use ($experiment) {
            return $experiment->nodes
                ->filter(fn ($node) => $node->pages_stored && in_array($page, collect($node->pages_stored)->pluck('name')->toArray()))
                ->count();
        });

        return [
            'datasets' => [
                [
                    'label' => 'Nodes storing target page',
                    'data' => $amounts,
                ],
            ],
            'labels' => $pages->map(fn ($page) => "{$page}"),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
