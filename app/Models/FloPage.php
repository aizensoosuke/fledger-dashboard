<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FloPage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'flo_id',
        'experiment_id',
        'target_in_experiment_id',
        'filler_in_experiment_id',
    ];

    public function targetInExperiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class, 'target_in_experiment_id');
    }

    public function fillerInExperiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class, 'filler_in_experiment_id');
    }

    public function experiment(): BelongsTo
    {
        return $this->belongsTo(Experiment::class);
    }

    public function storedInNodes(): BelongsToMany
    {
        return $this->belongsToMany(Node::class);
    }
}
