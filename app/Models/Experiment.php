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
        'pages_amount',
        'bookmarked',
        'summary',
        'description',
        'ended_at',
        'target_page_id',
    ];

    protected $casts = [
        'bookmarked' => 'boolean',
    ];

    public static function latestExperiment(): ?self
    {
        return self::orderBy('id', 'desc')->first();
    }

    public function nodes(): HasMany
    {
        return $this->hasMany(Node::class);
    }

    public function targetFloPages(): HasMany
    {
        return $this->hasMany(FloPage::class, 'target_in_experiment_id');
    }

    public function fillerFloPages(): HasMany
    {
        return $this->hasMany(FloPage::class, 'filler_in_experiment_id');
    }

    public function floPages(): HasMany
    {
        return $this->hasMany(FloPage::class);
    }
}
