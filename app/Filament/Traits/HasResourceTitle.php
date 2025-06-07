<?php

namespace App\Filament\Traits;

trait HasResourceTitle
{
    public function getSubheading(): ?string
    {
        $resource = static::getResource();

        return $resource::getRecordTitle($this->record);
    }
}
