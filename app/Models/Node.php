<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Node extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_ACTIVE = 'active';

    public const STATUS_SUCCESS = 'success';

    public const STATUS_TIMEOUT = 'timeout';

    protected $fillable = [
        'name',
        'pages',
    ];

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }

    public function dataPoints(): HasMany
    {
        return $this->hasMany(DataPoint::class);
    }

    public function timelessDataPoints(): HasMany
    {
        return $this->hasMany(TimelessDataPoint::class);
    }

    public function floPages(): BelongsToMany
    {
        return $this->belongsToMany(FloPage::class);
    }

    protected function casts(): array
    {
        return [
            'pages' => 'array',
        ];
    }
}
