<?php

namespace App\Filament\Resources\ExperimentResource\Pages;

use App\Filament\Resources\ExperimentResource;
use App\Models\Experiment;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\EditRecord;

class EditExperiment extends EditRecord
{
    protected static string $resource = ExperimentResource::class;

    protected static ?string $title = 'Experiment';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
