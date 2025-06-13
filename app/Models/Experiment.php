<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experiment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'pages_amount', // legacy (backwards compatibility)
        'bookmarked',
        'summary',
        'description',
        'ended_at',
        'target_page_id', // legacy (backwards compatibility)
        'filler_amount',
        'target_amount',
        'target_pages',
        'targets_per_node',
    ];

    protected $casts = [
        'bookmarked' => 'boolean',
        'target_pages' => 'array',
        'filler_amount' => 'integer',
        'target_amount' => 'integer',
        'targets_per_node' => 'integer',
    ];

    public static function latestExperiment(): ?self
    {
        return self::orderBy('id', 'desc')->first();
    }

    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class);
    }
}
