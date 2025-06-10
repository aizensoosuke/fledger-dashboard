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
}
