<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class FloPageData extends Data
{
    public function __construct(
        public string $name,
        public string $id,
    ) {}
}
